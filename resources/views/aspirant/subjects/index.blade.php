@extends('layout.layout')

@section('main')
<div ng-controller="aspirantDashboardCtrl" ng-init="initSubjects();">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0">Subjects</h6>
      </div>
      <div class="d-flex align-items-center gap-2">
         <a href="{{ url('/aspirant/dashboard') }}" class="btn btn-sm btn-danger">
            <i class="ri-arrow-left-line"></i>
         </a>
      </div>
   </div>

   <div class="card">
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>SN.</th>
                     <th>Subject</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="item in subjects track by item.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{item.name}}</td>
                     <td>
                        <span ng-if="item.status != 1" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                        <span ng-if="item.status == 1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                     </td>
                  </tr>
                  <tr ng-if="!subjects.length">
                     <td colspan="3" class="text-center py-4">No subjects found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
@endsection
