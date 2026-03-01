@extends('layout.layout')

@section('main')
<div ng-controller="teacherDashboardCtrl" ng-init="init();" class="container-fluid px-0">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Teacher Dashboard</h6>
            <p class="text-neutral-600 mt-4 mb-0">Welcome back, @{{ teacherProfile.name || 'Teacher' }}.</p>
        </div>
        <span class="badge text-bg-light px-3 py-2">@{{ today }}</span>
    </div>

    <div class="card shadow-1 radius-12 border-0 mb-24">
        <div class="card-body p-20">
            <div class="row gy-3">
                <div class="col-lg-4 col-sm-6">
                    <p class="text-secondary-light mb-4">Name</p>
                    <h6 class="mb-0">@{{ teacherProfile.name || '-' }}</h6>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <p class="text-secondary-light mb-4">Email</p>
                    <h6 class="mb-0">@{{ teacherProfile.email || '-' }}</h6>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <p class="text-secondary-light mb-4">Mobile</p>
                    <h6 class="mb-0">@{{ teacherProfile.mobile || '-' }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-24">
        <div class="col-xxl-3 col-sm-6" ng-repeat="card in cards track by $index">
            <div class="card shadow-1 radius-8 h-100" ng-class="card.gradientClass">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center gap-3 mb-16">
                        <div class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center" ng-class="card.iconClass">
                            <i ng-class="card.icon + ' text-white'"></i>
                        </div>
                        <p class="fw-medium text-primary-light mb-1">@{{ card.label }}</p>
                    </div>
                    <h6 class="mb-0">@{{ card.value | number }}</h6>
                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center justify-content-between">
                        <span>Active: @{{ card.active | number }}</span>
                        <span>Inactive: @{{ card.inactive | number }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mb-24">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                        <h6 class="text-lg mb-0">Today Student Attendance</h6>
                    </div>
                    <div class="p-20">
                        <div class="d-flex gap-6">
                            <div ng-repeat="item in studentAttendanceToday track by item.code"
                                 class="h-44-px rounded"
                                 ng-class="item.bar_class"
                                 ng-style="{'width': (item.percent || 0) + '%'}"></div>
                        </div>
                        <div class="mt-32 d-flex flex-column gap-16">
                            <div ng-repeat="item in studentAttendanceToday track by item.code" class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-12-px h-12-px radius-2" ng-class="item.bar_class"></span>
                                    <span class="text-neutral-600">@{{ item.label }}</span>
                                </div>
                                <span class="fw-semibold text-primary-light">@{{ item.percent }}% (@{{ item.count }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                        <h6 class="text-lg mb-0">Today Teacher Attendance</h6>
                    </div>
                    <div class="p-20">
                        <div class="d-flex gap-6">
                            <div ng-repeat="item in teacherAttendanceToday track by item.code"
                                 class="h-44-px rounded"
                                 ng-class="item.bar_class"
                                 ng-style="{'width': (item.percent || 0) + '%'}"></div>
                        </div>
                        <div class="mt-32 d-flex flex-column gap-16">
                            <div ng-repeat="item in teacherAttendanceToday track by item.code" class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-12-px h-12-px radius-2" ng-class="item.bar_class"></span>
                                    <span class="text-neutral-600">@{{ item.label }}</span>
                                </div>
                                <span class="fw-semibold text-primary-light">@{{ item.percent }}% (@{{ item.count }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-12 col-xxl-4">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                        <h6 class="text-lg mb-0">My Last 30 Days</h6>
                    </div>
                    <div class="p-20">
                        <div class="d-flex flex-column gap-16">
                            <div ng-repeat="item in myAttendance track by item.code" class="d-flex justify-content-between">
                                <span class="text-neutral-600">@{{ item.label }}</span>
                                <span class="fw-semibold text-primary-light">@{{ item.count }}</span>
                            </div>
                            <hr class="my-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-neutral-600">Total Marked</span>
                                <span class="fw-semibold text-primary-light">@{{ myAttendanceTotal }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xxl-8">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                        <h6 class="text-lg mb-0">Recent Student Attendance</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-20">Date</th>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th class="pe-20">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="row in recentStudentAttendance track by $index">
                                    <td class="ps-20">@{{ row.date }}</td>
                                    <td>@{{ row.name }}</td>
                                    <td><span class="badge" ng-class="row.status_badge_class">@{{ row.status_label }}</span></td>
                                    <td class="pe-20">@{{ row.remark || '-' }}</td>
                                </tr>
                                <tr ng-if="!recentStudentAttendance.length">
                                    <td colspan="4" class="text-center text-secondary py-4">No attendance records found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
