@extends('layout.layout')

@section('main')
<div ng-controller="studentDetailsCtrl" ng-init="student_token={{$student_token}};getDetails();">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div class="">
            <h1 class="fw-semibold mb-4 h6 text-primary-light">Student Details</h1>
            <div class="">
                <a href="index.html" class="text-secondary-light hover-text-primary hover-underline">Dashboard </a>
                <a href="student-list.html" class="text-secondary-light hover-text-primary hover-underline"> /
                    Student</a>
                <span class="text-secondary-light">/ Student Details</span>
            </div>
        </div>
        <button type="button"
            class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6 bg-base text-primary-light bg-hover-primary-600">
            <span class="d-flex text-md">
                <i class="ri-lock-2-line"></i>
            </span>
            Login Details
        </button>
    </div>
    <div class="mt-24">
        <div class="card h-100">
            <div class="card-body p-24">

                 <div class="d-flex gap-32 flex-md-row flex-column">
                    <div class="max-w-300-px w-100 text-center">
                        <figure class="mb-24 w-120-px h-120-px mx-auto rounded-circle overflow-hidden">
                            <img src="{{url('assets/images/thumbs/student-details-img.png')}}" alt="Student Image" class="w-100 h-100 object-fit-cover">
                        </figure>
                        <h2 class="h6 text-primary-light mb-16 fw-semibold">

                            @{{student.name}}
                        </h2>
                        <p class="mb-0">Admission No: <span class="text-primary-600 fw-semibold">   @{{student.admission_no}}</span>
                        </p>
                        
                        <div class="mt-32 d-flex gap-16 w-100">
                            <button type="button"
                                class="btn border fw-medium border-danger-600 bg-hover-danger-200 text-danger-600 text-md d-flex justify-content-center align-items-center gap-8 flex-grow-1 px-12 py-8 radius-8"
                                data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                                <span class="d-flex text-lg">
                                    <i class="ri-delete-bin-2-line"></i>
                                </span>
                                Suspend
                            </button>
                            <a href="edit-student.html"
                                class="btn btn-primary-600 border fw-medium border-primary-600 text-md d-flex justify-content-center align-items-center gap-8 flex-grow-1 px-12 py-8 radius-8">
                                <span class="d-flex text-lg">
                                    <i class="ri-edit-line"></i>
                                </span>
                                Edit
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <span class="h-100 w-1-px bg-neutral-200"></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="pb-16 border-bottom d-flex align-items-center justify-content-between gap-20">
                            <h3 class="h6 text-primary-light text-lg mb-0 fw-semibold">Personal Info</h3>
                            <span class="bg-success-100 text-success-600 px-16 py-4 radius-4 fw-medium text-sm">Active</span>
                        </div>
                        <div class="mt-16 d-flex flex-column gap-8">
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Class</span>
                                <span class="fw-normal text-sm text-secondary-light">: 1 (A), 2(A), 3(A)</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Section</span>
                                <span class="fw-normal text-sm text-secondary-light">: A</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Roll No</span>
                                <span class="fw-normal text-sm text-secondary-light">: 10</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Gender</span>
                                <span class="fw-normal text-sm text-secondary-light">: @{{student.gender}}</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Date Of Birth</span>
                                <span class="fw-normal text-sm text-secondary-light">: @{{student.dob}}</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Category</span>
                                <span class="fw-normal text-sm text-secondary-light">: General</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Academic Year</span>
                                <span class="fw-normal text-sm text-secondary-light">: 2025-26</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Phone Number</span>
                                <span class="fw-normal text-sm text-primary-600">: @{{student.mobile}}</span>
                            </div>
                            <div class="d-flex gap-4">
                                <span class="fw-semibold text-sm text-primary-light w-110-px">Email</span>
                                <span class="fw-normal text-sm text-primary-600">: @{{student.email}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-16">
            <div class="d-flex align-items-center gap-2">
                <ul class="p-12 nav nav-pills bordered-tab" id="pills-tab" role="tablist">

                    <li class="nav-item">
                        <a href="javascript:;" ng-click="changeTab(1)" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': tab == 1 }">
                            <span class="d-flex tab-icon line-height-1 text-md">
                                <i class="ri-group-line"></i>
                            </span>
                            Student Details
                        </a>
                       
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" ng-click="changeTab(2)" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': tab == 2 }" >
                            <span class="d-flex tab-icon line-height-1 text-md">
                                <i class="ri-calendar-check-line"></i>
                            </span>
                            Attendance
                        </a>
                       
                    </li>
                    
                    <li class="nav-item">
                        <a href="javascript:;" ng-click="changeTab(3)" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': tab == 3 }"> 
                            <span class="d-flex tab-icon line-height-1 text-md">
                                <i class="ri-login-box-line"></i>
                            </span>
                            Leave
                        </a>
                      
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" ng-click="changeTab(4)" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 "  ng-class="{'active': tab == 4 }">
                            <span class="d-flex tab-icon line-height-1 text-md">
                                <i class="ri-money-dollar-box-line"></i>
                            </span>
                            Fees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" ng-click="changeTab(5)" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 "  ng-class="{'active': tab == 5 }">
                            <span class="d-flex tab-icon line-height-1 text-md">
                                <i class="ri-file-edit-line"></i>
                            </span>
                            Exam
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" role="tabpanel" ng-show="tab == 1 " ng-class="{'active show': tab == 1}">
                    @include('admin.students.details.student_details')
                </div>
                <div class="tab-pane fade" role="tabpanel" ng-show="tab == 2 " ng-class="{'active show': tab == 2}">
                    @include('admin.students.details.attendance')
                </div>
                <div class="tab-pane fade" role="tabpanel" ng-show="tab == 3 " ng-class="{'active show': tab == 3}">
                    @include('admin.students.details.leave')
                    
                </div>
                <div class="tab-pane fade" role="tabpanel" ng-show="tab == 4 " ng-class="{'active show': tab == 4}">
                    @include('admin.students.details.fees')
                    
                </div>
                <div class="tab-pane fade" role="tabpanel" ng-show="tab == 5 " ng-class="{'active show': tab == 5}">
                    @include('admin.students.details.exam')
                    
                </div>
            </div>

            
        </div>
    </div>
</div>
@endsection