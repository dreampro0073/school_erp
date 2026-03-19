@extends('layout.layout')

@section('main')
<div ng-controller="schoolManagementCtrl" ng-init="init();">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
         <h6 class="fw-semibold mb-0">School</h6>
      </div>
      <div class="d-flex align-items-center gap-2">
         <ul class="p-12 nav nav-pills bordered-tab">
            <li class="nav-item">
               <a href="javascript:;" ng-click="type = 'schedule'; typeSchedule()" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': type == 'schedule'}" >  <i class="ri-calendar-check-line"></i>Schedule</a>
               
            </li>
            <li class="nav-item">
               <a href="javascript:;" ng-click="type = 'classes'; typeClasses()" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': type == 'classes'}">  <i class="ri-group-line"></i>Classes</a>
               
            </li>
            <li class="nav-item">
               <a href="javascript:;" ng-click="type = 'exams'; typeExams()" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': type == 'exams'}">  <i class="ri-file-edit-line"></i>Exams</a>
              
            </li>
            <li class="nav-item">
                <a href="javascript:;" ng-click="type = 'results'; typeResults()" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 "  ng-class="{'active': type == 'results'}">  <i class="ri-book-line"></i>Results</a>
            </li>
        </ul>
      </div>

      
   </div>
   <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show" role="tabpanel" ng-if="type == 'schedule'" ng-class="{'active': type == 'schedule'}">
        <div class="card">
            @include('admin.school.schedule')
        </div>
      </div>
      <div class="tab-pane fade show" role="tabpanel" ng-if="type == 'classes'" ng-class="{'active': type == 'classes'}">
         @include('admin.school.classes')
      </div>
      <div class="tab-pane fade show" role="tabpanel" ng-if="type == 'exams'" ng-class="{'active': type == 'exams'}">
         @include('admin.school.exams')
      </div>
      <div class="tab-pane fade show" role="tabpanel" ng-if="type == 'results'" ng-class="{'active': type == 'results'}">
         @include('admin.school.results')
      </div>
   </div>
  <!--  <div class="card">
      <div class="card-body p-0">
         <div ng-if="type == 'schedule'">
            @include('admin.school.schedule')
         </div>         
         <div ng-if="type == 'classes'">
            @include('admin.school.classes')
         </div>         
         <div ng-if="type == 'exams'">
            @include('admin.school.exams')
         </div>         
         <div ng-if="type == 'results'">
            @include('admin.school.results')
         </div>
      </div>
   </div> -->

      
   @include('admin.school.models')

</div>
@endsection
