@extends('admin.layout')

@section('main')
<div ng-controller="dashboardCtrl" ng-init="init();">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0">Dashboard</h6>
         <p class="text-neutral-600 mt-4 mb-0">School -> Manage your school, track attendance, expense, and net worth.</p>
      </div>
   </div>

   <div class="row gy-4">
      <div class="col-xxl-8">
         <div class="row gy-4">
            <div class="col-xxl-4 col-sm-6" ng-repeat="card in cards track by $index">
               <a ng-if="card.url" ng-href="@{{card.url}}" class="text-decoration-none">
                  <div class="card shadow-1 radius-8 h-100" ng-class="card.gradient">
                     <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                           <div class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center" ng-class="card.bg">
                              <img ng-src="assets/images/icons/@{{card.icon}}" alt="Icon">
                           </div>
                           <p class="fw-medium text-primary-light mb-1">@{{card.label}}</p>
                        </div>
                        <h6 class="mb-0">@{{card.mainValue | number}}</h6>
                        <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                           <span class="d-inline-flex align-items-center gap-1 text-success-600 text-sm fw-semibold">
                              Active: @{{card.active | number}}
                           </span>
                           <span>Inactive: @{{card.inactive | number}}</span>
                        </p>
                     </div>
                  </div>
               </a>
            </div>
         </div>
      </div>
      <div class="col-xxl-4">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom border-neutral-200 pb-16 mb-16">
                  <h6 class="text-lg mb-0">Student Attendance</h6>
               </div>
               <div class="d-flex flex-column gap-12">
                  <div class="d-flex align-items-center justify-content-between">
                     <span class="text-neutral-600">Present</span>
                     <span class="fw-semibold text-primary-light">87%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                     <span class="text-neutral-600">Absent</span>
                     <span class="fw-semibold text-primary-light">40%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                     <span class="text-neutral-600">Late</span>
                     <span class="fw-semibold text-primary-light">20%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                     <span class="text-neutral-600">Half day</span>
                     <span class="fw-semibold text-primary-light">20%</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
