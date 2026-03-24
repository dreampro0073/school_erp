@extends('layout.layout')

@section('main')
<div ng-controller="classManagementCtrl" ng-init="class_id= {{$class_id}}; initClass();">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
         <h6 class="fw-semibold mb-0">Class : @{{ standard.section_name}}<span ng-if="standard.section_name">(@{{standard.section_name}})</span></h6>
      </div>
      <div class="d-flex align-items-center gap-2">
         <ul class="p-12 nav nav-pills bordered-tab" id="pills-tab" role="tablist">

            <li class="nav-item">
               <a href="javascript:;" ng-click="type = 'students'; initClass()" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': type == 'students'}">  <i class="ri-group-line"></i>Students</a>
            </li>            

            <li class="nav-item">
               <a href="javascript:;" ng-click="type = 'subjects'; initClass()" class="nav-link d-flex align-items-center gap-8 text-secondary-light fw-medium text-sm text-hover-primary-600 text-capitalize bg-transparent px-20 py-12 " ng-class="{'active': type == 'subjects'}">  <i class="ri-group-line"></i>Subjects</a>
            </li>
        </ul>
      </div>

      
   </div>
   <div class="tab-content" id="pills-tabContent">
     
      <div ng-show="!loading">
         <div class="tab-pane fade" role="tabpanel" ng-show="type == 'students'" ng-class="{'active show tab-pane-animate': type == 'students'}">

          @include('admin.school.class_students')
         </div>
         <div class="tab-pane fade" role="tabpanel" ng-show="type == 'subjects'" ng-class="{'active show tab-pane-animate': type == 'subjects'}">
       
            @include('admin.school.class_subjects')
         </div>
      </div>

   </div>
</div>
@endsection
