@extends('layout.layout')

@section('main')
<div ng-controller="classManagementCtrl" ng-init="class_id= {{$class_id}}; initClass();">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
         <h6 class="fw-semibold mb-0">School</h6>
      </div>
      <div class="d-flex align-items-center gap-2">
         <ul class="p-12 nav nav-pills bordered-tab" id="pills-tab" role="tablist">

            <li class="nav-item">
               <a href="javascript:;" ng-click="type = 'classes'; typeClasses()" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': type == 'classes'}">  <i class="ri-group-line"></i>Classes</a>
               
            </li>
        </ul>
      </div>

      
   </div>
   <div class="tab-content" id="pills-tabContent">
     
      <div ng-show="!loading">
         <div class="tab-pane fade" role="tabpanel"
           ng-show="type == 'schedule'"
           ng-class="{'active show tab-pane-animate': type == 'schedule'}">

          @include('admin.school.class_students')
         </div>
         <div class="tab-pane fade" role="tabpanel"
           ng-show="type == 'classes'"
           ng-class="{'active show tab-pane-animate': type == 'classes'}">
       
            @include('admin.school.class_subjects')
         </div>
      </div>

   </div>
</div>
@endsection
