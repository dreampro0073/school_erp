@extends('layout.layout')

@section('main')

<div ng-controller="workLogCtrl" ng-init="init();">
   <div class="mt-24">
      <div class="row">
         <div class="col-md-6">
            <h5 class="">Worklog</h5>
         </div>
         <div class="col-md-6">
            <button type="button" class="btn btn-warning" ng-click="openAddModal()">ADD / EDIT</button>
         </div>
      </div>

      <form class="row g-3 mb-16" ng-submit="init()">

         <div class="col-md-3 form-group">
            <label class="form-label">From Date</label>
            <input class="flatpickr flatpickr-input form-control" type="text" ng-model="filter.from_date" placeholder="Select Date..">
         </div>

         <div class="col-md-3 form-group">
            <label class="form-label">To Date</label>
            <input class="flatpickr flatpickr-input form-control" type="text" ng-model="filter.to_date" placeholder="Select Date..">
         </div>

         <div class="col-md-3 form-group">
            <label class="form-label">User</label>
            <select class="form-control" ng-model="filter.user_id" >
               <option value="">All Users</option>
               <option ng-repeat="user in users" ng-value="user.id">@{{user.name}}</option>
            </select>
         </div>

         <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <button type="button" class="btn btn-light" ng-click="resetFilter()">Reset</button>
         </div>
      </form>

      <div class="card h-100">
         <div class="card-body">

            <div class="table-responsive">
               <table class="table bordered-table mb-0">
                  <thead>
                     <tr>
                        <th>SN.</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Remark</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr ng-repeat="item in worklog">
                        <td>@{{ $index+1 }}</td>
                        <td>@{{ item.date }}</td>
                        <td>@{{ item.name }}</td>
                        <td>@{{ item.remark}}</td>
                     </tr>
                  </tbody>
               </table>
               <div ng-if="worklog.length == 0" class="alert alert-danger">No worklog entries found.</div>
            </div>
         </div>
      </div>
   </div>

   <div class="modal fade" id="worklogModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Worklog</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form ng-submit="saveWorklog()">

               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-7 form-group">
                        <label class="form-label">Date</label>
                        <input class="flatpickr flatpickr-input form-control" ng-change="getDayData()" type="text" ng-model="formData.date" placeholder="Select Date..">
                     </div>
                     <div class="col-md-3" ng-if="formData.date">
                        <button ng-click="addMoreItem()" type="button" class="btn btn-sm btn-primary">Add More</button>
                     </div>
                  </div>
                  <hr>
                  <div class="row" ng-if="formData.date" ng-repeat="row in formData.day_data">
                     <div class="col-md-6 form-group">
                        <label class="form-label">Remark</label>
                        <input type="text" class="form-control" ng-model="row.remark" required>
                     </div>

                     <div class="col-md-3 form-group">
                        <label class="form-label">Hours</label>
                        <input type="text" class="form-control" ng-model="row.hours" required>
                     </div>                     

                     <div class="col-md-3">
                        <button ng-click="removeItem($index)" type="button" class="btn btn-sm btn-danger">Delete</button>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success-600" ng-disabled="processing">
                     @{{ processing ? 'Saving...' : 'Save' }}
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>


</div>
@endsection
