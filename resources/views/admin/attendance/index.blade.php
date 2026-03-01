@extends('admin.layout')

@section('main')
<div ng-controller="attendanceCtrl" ng-init="init();" class="mt-24">
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h5 class="mb-0">Attendance</h5>
      <button type="button" class="btn btn-primary" ng-click="saveAttendance()" ng-disabled="saving || !attendanceItems.length">
         <i class="ri-save-line"></i> @{{saving ? 'Saving...' : 'Save Attendance'}}
      </button>
   </div>

   <div class="card mb-16">
      <div class="card-body">
         <div class="row g-2">
            <div class="col-md-3">
               <label class="form-label">Type</label>
               <select class="form-select" ng-model="filters.type" ng-change="loadAttendance()">
                  <option value="student">Students</option>
                  <option value="teacher">Teachers</option>
               </select>
            </div>
            <div class="col-md-3">
               <label class="form-label">Date</label>
               <input type="date" class="form-control" ng-model="filters.date" ng-change="loadAttendance()">
            </div>
            <div class="col-md-4">
               <label class="form-label">Search</label>
               <input type="text" class="form-control" placeholder="Search by name or mobile" ng-model="filters.search" ng-change="applyListFilter()">
            </div>
            <div class="col-md-2 d-flex align-items-end">
               <button type="button" class="btn btn-outline-secondary w-100" ng-click="resetSearch()">Clear</button>
            </div>
         </div>
      </div>
   </div>

   <div class="card mb-16">
      <div class="card-header">
         <h6 class="mb-0">@{{filters.type === 'teacher' ? 'Teacher' : 'Student'}} Attendance - @{{filters.date}}</h6>
      </div>
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Name</th>
                     <th>Mobile</th>
                     <th>Status</th>
                     <th>Remark</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="item in attendanceItems track by item.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{item.name}}</td>
                     <td>@{{item.mobile || '-'}}</td>
                     <td>
                        <select class="form-select form-select-sm" ng-model="item.status">
                           <option ng-repeat="status in statuses track by status.code" ng-value="status.code">@{{status.label}}</option>
                        </select>
                     </td>
                     <td>
                        <input type="text" class="form-control form-control-sm" ng-model="item.remark" placeholder="Optional remark">
                     </td>
                  </tr>
                  <tr ng-if="!attendanceItems.length">
                     <td colspan="5" class="text-center py-4 text-secondary">No records found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <div class="card">
      <div class="card-header">
         <h6 class="mb-0">Attendance History</h6>
      </div>
      <div class="card-body">
         <div class="row g-2 mb-12">
            <div class="col-md-3">
               <label class="form-label">Type</label>
               <select class="form-select" ng-model="historyFilter.type">
                  <option value="">All</option>
                  <option value="student">Students</option>
                  <option value="teacher">Teachers</option>
               </select>
            </div>
            <div class="col-md-3">
               <label class="form-label">From Date</label>
               <input type="date" class="form-control" ng-model="historyFilter.from_date">
            </div>
            <div class="col-md-3">
               <label class="form-label">To Date</label>
               <input type="date" class="form-control" ng-model="historyFilter.to_date">
            </div>
            <div class="col-md-3 d-flex align-items-end">
               <button type="button" class="btn btn-outline-primary w-100" ng-click="loadHistory()">Apply Filter</button>
            </div>
         </div>
         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>Date</th>
                     <th>Type</th>
                     <th>Name</th>
                     <th>Status</th>
                     <th>Remark</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="row in historyRows track by row.id">
                     <td>@{{row.date}}</td>
                     <td class="text-capitalize">@{{row.type}}</td>
                     <td>@{{row.name}}</td>
                     <td><span class="badge" ng-class="row.status_badge_class">@{{row.status_label}}</span></td>
                     <td>@{{row.remark || '-'}}</td>
                  </tr>
                  <tr ng-if="!historyRows.length">
                     <td colspan="5" class="text-center py-4 text-secondary">No history found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
@endsection
