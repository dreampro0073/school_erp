<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Http\Request;
use DB, Session, Cache, Validator, Auth, Response;
use App\Models\User,App\Models\ComFinReport, App\Models\Industry, App\Models\Company, App\Models\UserAccess,App\Models\LoginToken ;


class DashboardController extends Controller {

    public function dashboard(Request $request){
        
        LoginToken::deleteAccessToken();

        if($request->financial_year){
            $financial_year = $request->financial_year;
        } else {
            $financial_year = 2025;
        }

        $sidebar = 'dashboard';
        $subsidebar = 'dashboard';

        $check = DB::table('user_read_news')->where('user_id', Auth::id())->where('status', 1)->first();

        $is_new_news = 0;
        
        if(isset($check)) {
            $is_new_news = 1;

        }

        Session::put('is_new_news', $is_new_news);

        return view('admin.dashboard',[
            "financial_year" => $financial_year,
            "sidebar" => $sidebar,
            "subsidebar" => $subsidebar,
            "is_new_news" => $is_new_news,
        ]);
    }

    public function dashboard_init(Request $request){

        $financial_year = $request->financial_year;
        if(Auth::id() != Auth::user()->parent_user_id){
            $company_ids = DB::table("user_access")->distinct("company_id")->where("financial_year", $financial_year)->where("user_id",Auth::id())->pluck("company_id")->toArray();
        } else {
            $company_ids = DB::table("companies")->where("active", 0)->where("user_id",Auth::id())->pluck("id as company_id")->toArray();
        }

        if(sizeof($company_ids) == 0) $company_ids = [0];

        $reports = DB::table("company_financial_reports")
            ->select("company_financial_reports.*","u1.name as started_by_user","u2.name as last_updated_by_user", "industries.industry","sub_industries.sub_industry", "companies.company_name","company_division.division_name")
            ->join('companies', 'company_financial_reports.company_id', '=', 'companies.id')
            ->leftJoin('company_division', 'company_financial_reports.company_division_id', '=', 'company_division.id')
            ->leftJoin('industries', 'company_financial_reports.industry_id', '=', 'industries.id')
            ->leftJoin('sub_industries', 'company_financial_reports.sub_industry_id', '=', 'sub_industries.id')
            ->leftJoin('users as u1',"u1.id","=","company_financial_reports.started_by")
            ->leftJoin('users as u2',"u2.id","=","company_financial_reports.last_updated_by")
            ->where("companies.user_id",Auth::user()->parent_user_id)
            ->where('companies.active', 0)
            ->where("company_financial_reports.financial_year",$financial_year)
            ->where("company_financial_reports.active", 0)
            ->whereIn('company_financial_reports.company_id', $company_ids);
            if (Auth::user()->q_to_q == 0) {
                $reports = $reports->where("company_financial_reports.session_type", 0);
            }
        
        $reports = $reports->orderBy("companies.company_name","ASC")->orderBy("company_financial_reports.quarter_session", "ASC")->get();

        $final_reports = [];
        $checker_access = Auth::user()->checker_mode_access == 1 ? true : false;

        foreach ($reports as $report) {
            if($report->start_date){
                $report->start_date = date("d-m-Y",strtotime($report->start_date));
            }
            if($report->last_update){
                $report->last_update = date("d-m-Y H:i:s",strtotime($report->last_update));
            }

            $report->sub_divisions = ComFinReport::countSubDivisions($report);

            $check_auth = DB::table("user_access")->distinct("user_role")->where("company_id",$report->company_id)->where("division_id",$report->company_division_id)->where("user_id",Auth::id())->where("financial_year", $financial_year)->where('quarter_session', $report->quarter_session)->get();
            
            if(sizeof($check_auth) > 0 ){
                foreach ($check_auth as $value) {
                    if ($value->user_role == 1) {
                       $report->maker = true;
                    }
                    
                    if ($value->user_role == 2 && $checker_access) {
                        $report->checker = true;
                    }
                }
                $final_reports[] = $report;
            } else {

            }
        }
        
        $sub_industries = DB::table("sub_industries")->orderBy("sub_industry", "ASC")->get();
        $industries = Industry::orderBy("industry", "ASC")->get();
        
        $companies = Company::where("user_id", Auth::user()->parent_user_id)->where('companies.active', 0);
        $companies = $companies->whereIn("companies.id",$company_ids);
        $companies = $companies->get();

        $divisions = DB::table("company_division")->select("company_division.id","company_division.division_name","company_division.company_id")->join("companies","companies.id","=","company_division.company_id")->where('company_division.status', 0)->where("companies.active", 0)->where("companies.user_id", Auth::user()->parent_user_id)->whereIn("companies.id",$company_ids);
        
        $divisions = $divisions->get();

        if(Session::has('report_id')){
            $report_id = Session::get('report_id');
            if ($report_id > 0) {
                
                $report = ComFinReport::find($report_id);
                $user_role = Session::get('user_role');

                $access_pages =Session::get('page_access');

                if ($user_role == 1) {
                    $page_lock_status = DB::table("report_saving_logs");
                } 
                if ($user_role == 2){
                    $page_lock_status = DB::table("check_page_locked");
                }

                $locked_pages = $page_lock_status->where("status", 1)->whereIn("page_id", $access_pages)->where("report_id", $report_id)->pluck('page_id', 'id')->toArray();
                if(sizeof($locked_pages) > 0){
                    $data['progress_status'] = round((sizeof($locked_pages) * 100) / sizeof($access_pages), 2);  
                } else {
                    $data['progress_status'] = 0;
                }
            }
        }

        $check = DB::table('user_read_news')->where('user_id', Auth::id())->where('status', 1)->first();

        $is_new_news = false;
        
        if ($check) {
            $news = DB::table('whats_new')->where('flag', 0)->orderBy('id', 'DESC')->first();
            if(Session::get('ignore_n') != 1 && $news){
                $data['news'] = $news;
                $is_new_news = true;
            }
        }

        $inactive_reports = DB::table("company_financial_reports")
            ->select("company_financial_reports.*","u1.name as started_by_user", "industries.industry","sub_industries.sub_industry", "companies.company_name","company_division.division_name")
            ->join('companies', 'company_financial_reports.company_id', '=', 'companies.id')
            ->leftJoin('company_division', 'company_financial_reports.company_division_id', '=', 'company_division.id')
            ->leftJoin('industries', 'company_financial_reports.industry_id', '=', 'industries.id')
            ->leftJoin('sub_industries', 'company_financial_reports.sub_industry_id', '=', 'sub_industries.id')
            ->leftJoin('users as u1',"u1.id","=","company_financial_reports.started_by")
            ->where("companies.user_id",Auth::user()->parent_user_id)
            ->where('companies.active', 0)
            ->where("company_financial_reports.financial_year",$financial_year)
            ->where("company_financial_reports.active", 1)
            ->whereIn('company_financial_reports.company_id', $company_ids);
            if (Auth::user()->q_to_q == 0) {
                $inactive_reports = $inactive_reports->where("company_financial_reports.session_type", 0);
            }
        
        $inactive_reports = $inactive_reports->orderBy("companies.company_name","ASC")->orderBy("company_financial_reports.quarter_session", "ASC")->get();
        $ia_reports = [];
        foreach ($inactive_reports as $ia_report) {
            if($ia_report->company_division_id > 0){
                $check = DB::table("company_division")->where("id", $ia_report->company_division_id)->where("company_division.status", 0)->first();
                if($check)$ia_reports[] = $ia_report;
            } else {
                $ia_reports[] = $ia_report;
            }
        }
        $data["inactive_reports"] = $ia_reports;
        $data['companies'] = $companies;
        $data['divisions'] = $divisions;
        $data['pending_reports'] = [];
        $data['sub_industries'] = $sub_industries;
        $data['industries'] = $industries;
        $data['reports'] = $final_reports;
        $data['is_new_news'] = $is_new_news;
        return Response::json($data,200,[]);
    }

    public function ignoreNews(){
        Session::put("ignore_n",1);
        $data['success'] =true;
        return Response::json($data,200,array());
    }
    public function startIndustry(){
        $industries = Industry::orderBy('industry', 'ASC')->get();
        $data['industries'] = $industries;
        return Response::json($data,200,[]);
    }

    public function getIndustry($sub_industry_id){
        $industry_ids = [];
        $sub_industry = DB::table("sub_industries")->find($sub_industry_id);
        
        if($sub_industry){
            $industry_ids = explode(',', $sub_industry->industry_ids);
        }

        $industries = DB::table("industries")->whereIn("id", $industry_ids)->get();
        $data['industries'] = $industries;
        return Response::json($data,200,[]);
    }

    public function create(Request $request)
    {
        $success = true;
        $message = "";

        $validator = Validator::make($request->all(), [
            'company_id' => 'required', 
            'financial_year' => 'required', 
            'industry_id' => 'required', 
        ]);

        if ($validator->fails()) {
            $message = "All Fileds are Required";
            $success = false;         
            return Response::json($data,200,[]);
        } else {

            if(!$request->company_division_id){
                $request->company_division_id = 0;
            }

            if ($request->has('id')) {

                $report = ComFinReport::find($request->id);
                $report->industry_id = $request->industry_id;
                $report->last_updated_by = Auth::id();
                $report->status = 1;
                $report->additional_required = $request->additional_required;
                $report->save();
                $message = 'Report details are successfully updated';

            } else {

                if (Auth::user()->q_to_q == 1) {
                    $check = ComFinReport::where("company_id", $request->company_id)->where('company_division_id',$request->company_division_id)->where('financial_year',$request->financial_year)->where('session_type', $request->session_type)->where('quarter_session', $request->quarter_session)->first();
                    
                } else {
                    $check = ComFinReport::where("company_id", $request->company_id)->where('company_division_id',$request->company_division_id)->where('financial_year',$request->financial_year)->where('session_type', 0)->where('quarter_session', 0)->first();

                }

                if($check){
                    $success = false;
                    $message = "Report for this is already added";
                } else {

                    $report = new ComFinReport;
                    $report->company_id = $request->company_id;
                    $report->company_division_id = $request->company_division_id;

                    $report->financial_year = $request->financial_year;
                    $report->session_type = $request->has('session_type') ? $request->session_type : 0;
                    $report->quarter_session = $request->has('quarter_session') ? $request->quarter_session : 0;
                    $report->additional_required = $request->has('additional_required') ? $request->additional_required : 1;
                    $report->industry_id = $request->industry_id;
                    $report->started_by = Auth::id();
                    $report->start_date = date("Y-m-d");
                    $report->last_updated_by = Auth::id();
                    $report->status = 1;
                    $report->save();
                    $message = 'Report details are successfully created';

                    if($request->quarter_session > 0){
                        $page_access_ids = [4,9,11,13,14];
                    } else{
                        if ($request->company_division_id > 0) {
                            $page_access_ids = [4,9,11,13,14,17];
                        } else {
                            $page_access_ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17];
                        }
                    }

                    foreach ($page_access_ids as $value) {
                    
                        $new_company_access = new UserAccess;
                        $new_company_access->user_id = Auth::id();
                        $new_company_access->company_id = $request->company_id;
                        $new_company_access->type = 1;
                        $new_company_access->division_id = $request->company_division_id;
                        $new_company_access->user_role = 1;
                        $new_company_access->page_access_id = $value;
                        $new_company_access->financial_year = $request->financial_year;
                        $new_company_access->quarter_session = $request->quarter_session ? $request->quarter_session : 0;
                        $new_company_access->save();
                    }
                   
                }
                
            }

        }

        $data['success'] = $success;
        $data['message'] = $message;

        return Response::json($data,200,[]);

    }    

    public function startDivReport(Request $request){

        if($request->company_id && $request->financial_year && $request->industry_id){
            $check = DB::table("company_financial_reports")->where("financial_year", $request->financial_year)->where("started_by", Auth::id())->where("company_id", $request->company_id)->where("company_division_id", $request->division_id)->where("industry_id", $request->industry_id)->first();
            
            if (!$check) {
                        
                $report = new ComFinReport;
                $report->company_id = $request->company_id;
                $report->company_division_id = $request->division_id;
                $report->financial_year = $request->financial_year;
                $report->industry_id = $request->industry_id;
                $report->started_by = Auth::id();
                $report->start_date = date("Y-m-d");
                $report->last_updated_by = Auth::id();
                $report->status = 1;
                $report->save();
                $success = true;         
                $message = 'Report is successfully created';
            }else{
                $message = "This Report is already added";
                $success = false;
            }
             
        } else {     
            $message = "All Fileds are Required";
            $success = false; 
        }       

        $data['success'] = $success;
        $data['message'] = $message;

        return Response::json($data,200,[]);

    }

    public function resumeReport(Request $request, $user_role)
    {
        ComFinReport::where('started_by',Auth::id())->update(["status"=>0]);
        
        $report = ComFinReport::where('id',$request->id)->update(["status"=>1]);

        $report_details = ComFinReport::select('company_financial_reports.*','companies.company_name','company_division.division_name')->join('companies','companies.id','=','company_financial_reports.company_id')->leftJoin('company_division', 'company_financial_reports.company_division_id', '=', 'company_division.id')->where("company_financial_reports.id",$request->id)->first();


        $access_pages = DB::table("user_access")
        ->where("user_id", Auth::user()->id)
        ->where("company_id", $report_details->company_id)
        ->where("division_id", $report_details->company_division_id)
        ->where("financial_year", $report_details->financial_year)
        ->where("user_role", $user_role)
        ->where("quarter_session", $report_details->quarter_session);
        if($report_details->quarter_session > 0){
            $access_pages = $access_pages->whereIn('user_access.page_access_id', ['4','9','11','13','14']);
        }

        $access_pages = $access_pages->distinct('page_access_id')->orderBy("page_access_id")->pluck("page_access_id")->toArray();
       
        Session::put('report_id',$request->id);

        Session::put("page_access",$access_pages);
        Session::put("user_role", $user_role);

        Session::put("REPORT_COM_NAME",$report_details->company_name);
        Session::put("REPORT_YEAR",$report_details->financial_year);
        Session::put("REPORT_DIVISION",$report_details->division_name);

        if($report_details->company_division_id == 0){
            Session::put("REPORT_TYPE","centralized");
        } else {
            Session::put("REPORT_TYPE","division");
        }

        $data['success'] = true;
        $data['message'] = 'Report resumed successfully';  
        return Response::json($data,200,[]);
    }

    public function stopReport(){
        Session::put('report_id', '');
        Session::put('user_role', Null);
        $data['success'] = true;
        return Response::json($data,200,[]);
    }

    public function editReport($report_id) 
    {
        $report = DB::table("company_financial_reports")->find($report_id);
        $data["report"] = $report; 
        return Response::json($data,200,[]);
    }

    public function validateReportId(Request $request)
    {
        $flag = true;
        if(Session::has('report_id')){
            if(Session::get("report_id") != $request->input("report_id")){
                $flag = false;
            }
        }

        $data['success'] = $flag;
        return json_encode($data);
    }

    public function activeReport(Request $request){
        $report = DB::table("company_financial_reports")
        ->where("company_financial_reports.active", 1)
        ->where("company_financial_reports.id", $request->report_id)
        ->first(); 

        $flag = true;
        $message = 'Report Successfully Activated!';

        if($report){
            $company = DB::table("companies")->where('companies.active', 0)->where("companies.id", $report->company_id)->where("companies.user_id",Auth::user()->parent_user_id)->first();
            if(!$company){
                $flag = false;
                $message = "Company Not Found!";
            }

            if($flag && $report->company_division_id > 0){
                $check = DB::table("company_division")->where("company_id", $report->company_id)->where("id", $report->company_division_id)->where("status", 0)->first();
                
                if(!$check){
                    $flag = false;
                    $message = "Division Not Found!";
                }
            }

        } else {
            $flag = false;
            $message = "Report Not Found!";
        } 

        if($flag){
            $report = ComFinReport::find($request->report_id);
            $report->active = 0;
            $report->save();  
        } 

        $data['success'] = $flag;
        $data['message'] = $message;
        return Response::json($data, 200, []);

    }
}
