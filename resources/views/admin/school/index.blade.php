@extends('layout.layout')

@section('main')
<div ng-controller="schoolManagementCtrl" ng-init="init();">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0">School</h6>
      </div>
      <div class="d-flex align-items-center gap-2">
         <ul class="p-12">
            <li>
               <a href="javascript:;" ng-click="type = 'schedule'; typeSchedule()" class="gap-2 py-6">Schedule</a>
               <a href="javascript:;" ng-click="type = 'classes'; typeClasses()" class="gap-2 py-6">Classes</a>
               <a href="javascript:;" ng-click="type = 'exams'; typeExams()" class="gap-2 py-6">Exams</a>
               <a href="javascript:;" ng-click="type = 'results'; typeResults()" class="gap-2 py-6">Results</a>
            </li>
        </ul>
      </div>
   </div>

   <div class="card">
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
   </div>

      
   @include('admin.school.models')

</div>
@endsection
