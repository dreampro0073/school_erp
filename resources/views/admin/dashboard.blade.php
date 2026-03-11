@extends('layout.layout')

@section('main')
<div ng-controller="adminDashboardCtrl" ng-init="init();" class="container-fluid px-0">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Dashboard</h6>
            <p class="text-neutral-600 mt-4 mb-0">School -> Manage your school, track attendance, expense, and net worth.</p>
        </div>
    </div>

    <div class="mt-24">
        <div class="row gy-4">
            <div class="col-xxl-8">
                <div class="row gy-4">
                    <div class="col-xxl-4 col-sm-6">
                        <div class="card shadow-1 radius-8 h-100">
                            <a href="{{ url('/admin/teachers') }}">
                                <div class="card-body p-20">
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                        <div class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center" >
                                            <i class="ri-circle-fill circle-icon w-auto"></i>
                                        </div>
                                        <p class="fw-medium text-primary-light mb-1">Teachers</p>
                                    </div>
                                    <h6 class="mb-0">@{{teachers['total_teachers']}}</h6>
                                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center justify-content-between">
                                        <span>Active: @{{teachers['active_teachers']}}</span>
                                        <span>Inactive: @{{teachers.inactive_teachers}}</span>
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-sm-6">
                        <div class="card shadow-1 radius-8 h-100">
                            <a href="{{ url('/admin/students') }}">
                                <div class="card-body p-20">
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                        <div class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center" >
                                            <i class="ri-circle-fill circle-icon w-auto"></i>
                                        </div>
                                        <p class="fw-medium text-primary-light mb-1">Students</p>
                                    </div>
                                    <h6 class="mb-0">@{{students['total_students']}}</h6>
                                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center justify-content-between">
                                        <span>Active: @{{students['active_students']}}</span>
                                        <span>Inactive: @{{students.inactive_students}}</span>
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- *** HOLD *** -->
            @if(false) 
            <div class="col-xxl-4">
                <div class="card h-100">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                            <h6 class="text-lg mb-0">Student Attendance</h6>
                        </div>
                        <div class="p-20">
                            <div class="d-flex gap-6">
                                <div ng-repeat="item in attendance track by item.key"
                                     class="h-44-px rounded"
                                     ng-class="item.barClass"
                                     ng-style="{'width': (item.percent || 0) + '%'}"
                                     title="@{{item.label}}: @{{item.percent}}%">
                                </div>
                            </div>
                            <div class="mt-32 d-flex flex-column gap-24">
                                <div ng-repeat="item in attendance track by item.key" class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-12-px radius-2" ng-class="item.barClass"></span>
                                        <span class="text-neutral-600">@{{item.label}}</span>
                                    </div>
                                    <span class="fw-semibold text-primary-light">@{{item.percent}}% (@{{item.count}})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
