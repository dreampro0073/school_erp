<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendanceController extends Controller {
    public function index() {
        return view('admin.attendance.index');
    }

    public function init(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $type = $request->type ? $request->type : 'teacher';
        $date = $request->date ? date("Y-m-d", strtotime($request->date)) : date("Y-m-d");
        $search = $request->search ? trim($request->search) : '';

        $statuses = [];
        $default_status = 'present';

        if (Schema::hasTable('attendance_statuses')) {
            $status_rows = AttendanceStatus::where('active', 1)->orderBy('sort_order')->get();
            foreach ($status_rows as $status) {
                $statuses[] = [
                    'code' => $status->code,
                    'label' => $status->label,
                    'badge_class' => $status->badge_class ? $status->badge_class : 'bg-neutral-100 text-neutral-700',
                    'bar_class' => $status->bar_class ? $status->bar_class : 'bg-primary-600',
                    'is_default' => $status->is_default ? true : false,
                ];

                if ($status->is_default == 1) {
                    $default_status = $status->code;
                }
            }
        }

        if (sizeof($statuses) == 0) {
            $statuses = [
                [
                    'code' => 'present',
                    'label' => 'Present',
                    'badge_class' => 'bg-success-100 text-success-600',
                    'bar_class' => 'bg-success-600',
                    'is_default' => true,
                ],
                [
                    'code' => 'late',
                    'label' => 'Late',
                    'badge_class' => 'bg-warning-100 text-warning-600',
                    'bar_class' => 'bg-warning-600',
                    'is_default' => false,
                ],
                [
                    'code' => 'absent',
                    'label' => 'Absent',
                    'badge_class' => 'bg-danger-100 text-danger-600',
                    'bar_class' => 'bg-danger-600',
                    'is_default' => false,
                ],
                [
                    'code' => 'half_day',
                    'label' => 'Half Day',
                    'badge_class' => 'bg-info-100 text-info-600',
                    'bar_class' => 'bg-info-600',
                    'is_default' => false,
                ],
            ];
        }

        $attendance_map = [];
        if (Schema::hasTable('attendances')) {
            $rows = Attendance::where('client_id', $user->client_id)
                ->where('type', $type)
                ->where('attendance_date', $date)
                ->get();

            foreach ($rows as $row) {
                $attendance_map[$row->reference_id] = $row;
            }
        }

        $attendance_items = [];

        if ($type == 'student') {
            $query = Student::select(
                'students.id',
                'students.first_name',
                'students.last_name',
                'students.name',
                'students.mobile',
                'students.admission_no',
                'standards.name as standard_name',
                'sections.name as section_name'
            )
            ->leftJoin('standards', 'standards.id', '=', 'students.standard_id')
            ->leftJoin('sections', 'sections.id', '=', 'students.section_id')
            ->where('students.school_id', $user->client_id);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('students.first_name', 'like', '%'.$search.'%')
                      ->orWhere('students.last_name', 'like', '%'.$search.'%')
                      ->orWhere('students.name', 'like', '%'.$search.'%')
                      ->orWhere('students.mobile', 'like', '%'.$search.'%')
                      ->orWhere('students.admission_no', 'like', '%'.$search.'%');
                });
            }

            $items = $query->orderBy('students.first_name')->orderBy('students.last_name')->get();

            foreach ($items as $item) {
                $name = trim(($item->first_name ? $item->first_name : '') . ' ' . ($item->last_name ? $item->last_name : ''));
                if (!$name) {
                    $name = $item->name ? $item->name : 'Student #'.$item->id;
                }

                $meta = trim(($item->standard_name ? $item->standard_name : '') . (($item->standard_name && $item->section_name) ? ' - ' : '') . ($item->section_name ? $item->section_name : ''));
                $attendance = isset($attendance_map[$item->id]) ? $attendance_map[$item->id] : null;

                $attendance_items[] = [
                    'id' => $item->id,
                    'code' => $item->admission_no ? $item->admission_no : 'STU-'.$item->id,
                    'name' => $name,
                    'mobile' => $item->mobile,
                    'meta' => $meta ? $meta : '-',
                    'status' => $attendance ? $attendance->status_code : $default_status,
                    'remark' => $attendance ? $attendance->remark : '',
                ];
            }
        } else {
            $query = Teacher::select(
                'id',
                'erp_id',
                'first_name',
                'last_name',
                'name',
                'mobile',
                'qualification'
            )
            ->where('school_id', $user->client_id)
            ->where('status', '!=', 2);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%'.$search.'%')
                      ->orWhere('last_name', 'like', '%'.$search.'%')
                      ->orWhere('name', 'like', '%'.$search.'%')
                      ->orWhere('mobile', 'like', '%'.$search.'%')
                      ->orWhere('erp_id', 'like', '%'.$search.'%')
                      ->orWhere('qualification', 'like', '%'.$search.'%');
                });
            }

            $items = $query->orderBy('first_name')->orderBy('last_name')->get();

            foreach ($items as $item) {
                $name = trim(($item->first_name ? $item->first_name : '') . ' ' . ($item->last_name ? $item->last_name : ''));
                if (!$name) {
                    $name = $item->name ? $item->name : 'Teacher #'.$item->id;
                }

                $attendance = isset($attendance_map[$item->id]) ? $attendance_map[$item->id] : null;

                $attendance_items[] = [
                    'id' => $item->id,
                    'code' => $item->erp_id ? $item->erp_id : 'TCH-'.$item->id,
                    'name' => $name,
                    'mobile' => $item->mobile,
                    'meta' => $item->qualification ? $item->qualification : '-',
                    'status' => $attendance ? $attendance->status_code : $default_status,
                    'remark' => $attendance ? $attendance->remark : '',
                ];
            }
        }

        $summary = [];
        foreach ($statuses as $status) {
            $summary[$status['code']] = [
                'code' => $status['code'],
                'label' => $status['label'],
                'badge_class' => $status['badge_class'],
                'bar_class' => $status['bar_class'],
                'count' => 0,
            ];
        }

        if (Schema::hasTable('attendances')) {
            $counts = Attendance::select('status_code', DB::raw('count(*) as total'))
                ->where('client_id', $user->client_id)
                ->where('type', $type)
                ->where('attendance_date', $date)
                ->groupBy('status_code')
                ->get();

            foreach ($counts as $count) {
                if (isset($summary[$count->status_code])) {
                    $summary[$count->status_code]['count'] = (int) $count->total;
                }
            }
        }

        $data["success"] = true;
        $data["statuses"] = array_values($statuses);
        $data["default_status"] = $default_status;
        $data["attendance_items"] = $attendance_items;
        $data["summary"] = array_values($summary);

        return response()->json($data,200,[]);
    }

    public function store(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        if (!Schema::hasTable('attendances')) {
            $data["success"] = false;
            $data["message"] = "Attendance table not found. Please run latest migration.";
            return response()->json($data,200,[]);
        }

        $type = $request->type == 'student' ? 'student' : 'teacher';
        $date = $request->date ? date("Y-m-d", strtotime($request->date)) : date("Y-m-d");
        $items = is_array($request->items) ? $request->items : [];

        $valid_status = [];
        if (Schema::hasTable('attendance_statuses')) {
            $valid_status = AttendanceStatus::where('active', 1)->pluck('code')->toArray();
        }

        if (sizeof($valid_status) == 0) {
            $valid_status = ['present', 'late', 'absent', 'half_day'];
        }

        foreach ($items as $item) {
            if (!isset($item['id']) || $item['id'] <= 0) {
                continue;
            }

            $status = isset($item['status']) ? $item['status'] : 'present';
            if (!in_array($status, $valid_status)) {
                $status = 'present';
            }

            $attendance = Attendance::where('client_id', $user->client_id)
                ->where('type', $type)
                ->where('reference_id', $item['id'])
                ->where('attendance_date', $date)
                ->first();

            if (!$attendance) {
                $attendance = new Attendance;
                $attendance->client_id = $user->client_id;
                $attendance->type = $type;
                $attendance->reference_id = $item['id'];
                $attendance->attendance_date = $date;
                $attendance->created_at = date("Y-m-d H:i:s");
            }

            $attendance->status_code = $status;
            $attendance->remark = isset($item['remark']) ? trim($item['remark']) : null;
            $attendance->marked_by = $user->id;
            $attendance->updated_at = date("Y-m-d H:i:s");
            $attendance->save();
        }

        $data["success"] = true;
        $data["message"] = "Attendance saved successfully.";
        return response()->json($data,200,[]);
    }

    public function list(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $history_rows = [];
        if (!Schema::hasTable('attendances')) {
            $data["success"] = true;
            $data["history_rows"] = $history_rows;
            return response()->json($data,200,[]);
        }

        $type = $request->type ? $request->type : '';
        $from_date = $request->from_date ? date("Y-m-d", strtotime($request->from_date)) : date("Y-m-d", strtotime("-14 days"));
        $to_date = $request->to_date ? date("Y-m-d", strtotime($request->to_date)) : date("Y-m-d");

        $status_map = [];
        if (Schema::hasTable('attendance_statuses')) {
            $status_rows = AttendanceStatus::where('active', 1)->get();
            foreach ($status_rows as $status) {
                $status_map[$status->code] = [
                    'label' => $status->label,
                    'badge_class' => $status->badge_class ? $status->badge_class : 'bg-neutral-100 text-neutral-700',
                ];
            }
        }

        $query = Attendance::where('client_id', $user->client_id)
            ->where('attendance_date', '>=', $from_date)
            ->where('attendance_date', '<=', $to_date);

        if ($type) {
            $query->where('type', $type);
        }

        $rows = $query->orderBy('attendance_date', 'DESC')->orderBy('updated_at', 'DESC')->limit(250)->get();

        foreach ($rows as $row) {
            if ($row->type == 'student') {
                $person = Student::select('first_name', 'last_name', 'name')->where('id', $row->reference_id)->first();
                $name = $person ? trim(($person->first_name ? $person->first_name : '') . ' ' . ($person->last_name ? $person->last_name : '')) : '';
                if (!$name) {
                    $name = $person && $person->name ? $person->name : 'Deleted student';
                }
            } else {
                $person = Teacher::select('first_name', 'last_name', 'name')->where('id', $row->reference_id)->first();
                $name = $person ? trim(($person->first_name ? $person->first_name : '') . ' ' . ($person->last_name ? $person->last_name : '')) : '';
                if (!$name) {
                    $name = $person && $person->name ? $person->name : 'Deleted teacher';
                }
            }

            $status_label = ucwords(str_replace('_', ' ', $row->status_code));
            $status_badge_class = 'bg-neutral-100 text-neutral-700';

            if (isset($status_map[$row->status_code])) {
                $status_label = $status_map[$row->status_code]['label'];
                $status_badge_class = $status_map[$row->status_code]['badge_class'];
            }

            $history_rows[] = [
                'id' => $row->id,
                'date' => date("Y-m-d", strtotime($row->attendance_date)),
                'type' => $row->type,
                'name' => $name,
                'status_label' => $status_label,
                'status_badge_class' => $status_badge_class,
                'remark' => $row->remark,
            ];
        }

        $data["success"] = true;
        $data["history_rows"] = $history_rows;
        return response()->json($data,200,[]);
    }
}
