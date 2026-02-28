@extends('admin.layout')

@section('main')
<div ng-controller="adminDashboardCtrl" ng-init="init();" class="container-fluid px-0">
   <div class="row g-3">
      <div class="col-12 col-xxl-8">
         <div class="row g-3">
            <div class="col-12 col-sm-6 col-xl-4" ng-repeat="card in cards track by card.key">
               <a ng-if="card.url" ng-href="@{{card.url}}" class="text-decoration-none text-reset d-block h-100">
                  <div class="card h-100 border-0 shadow-sm">
                     <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                           <div class="rounded-circle p-2 d-inline-flex align-items-center justify-content-center" ng-class="card.iconBgClass">
                              <img ng-src="assets/images/icons/@{{card.icon}}" alt="@{{card.label}}" width="28" height="28">
                           </div>
                           <h6 class="mb-0">@{{card.label}}</h6>
                        </div>
                        <h4 class="mb-3 fw-bold">@{{card.mainValue | number}}</h4>
                        <div class="d-flex justify-content-between text-secondary small">
                           <span>Active: @{{card.active | number}}</span>
                           <span>Inactive: @{{card.inactive | number}}</span>
                        </div>
                     </div>
                  </div>
               </a>

               <div ng-if="!card.url" class="card h-100 border-0 shadow-sm">
                  <div class="card-body">
                     <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-2 d-inline-flex align-items-center justify-content-center" ng-class="card.iconBgClass">
                           <img ng-src="assets/images/icons/@{{card.icon}}" alt="@{{card.label}}" width="28" height="28">
                        </div>
                        <h6 class="mb-0">@{{card.label}}</h6>
                     </div>
                     <h4 class="mb-3 fw-bold">@{{card.mainValue | number}}</h4>
                     <div class="d-flex justify-content-between text-secondary small">
                        <span>Active: @{{card.active | number}}</span>
                        <span>Inactive: @{{card.inactive | number}}</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-12 col-xxl-4">
         <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
               <h6 class="mb-0">Student Attendance</h6>
            </div>
            <div class="card-body p-0">
               <ul class="list-group list-group-flush">
                  <li ng-repeat="item in attendance track by item.key" class="list-group-item d-flex justify-content-between align-items-center">
                     <span class="fw-medium">@{{item.label}}</span>
                     <span class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill" ng-class="item.badgeClass">@{{item.percent}}%</span>
                        <span class="text-secondary small">(@{{item.count}})</span>
                     </span>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
