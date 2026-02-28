@extends('admin.layout')

@section('main')
<div ng-controller="teacherCtrl" ng-init="init();" class="mt-24">
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h5 class="mb-0">Teachers</h5>
      <a href="{{ route('admin.teachers.add') }}" class="btn btn-primary">
         <i class="ri-add-line"></i> Add Teacher
      </a>
   </div>

   <div class="card h-100">
      <div class="card-header">
         <div class="row g-2">
            <div class="col-md-5">
               <input
                  type="text"
                  class="form-control"
                  placeholder="Search by name, mobile, email"
                  ng-model="filters.search"
                  ng-change="applyFilters()">
            </div>
            <div class="col-md-3">
               <select class="form-select" ng-model="filters.gender" ng-change="applyFilters()">
                  <option value="">All Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
               </select>
            </div>
            <div class="col-md-2">
               <select class="form-select" ng-model="filters.status" ng-change="applyFilters()">
                  <option value="">All Status</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
               </select>
            </div>
            <div class="col-md-2">
               <button type="button" class="btn btn-outline-secondary w-100" ng-click="resetFilters()">
                  Clear
               </button>
            </div>
         </div>
      </div>
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Name</th>
                     <th>DOB</th>
                     <th>Gender</th>
                     <th>Mobile</th>
                     <th>Email</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="item in teachers track by item.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{item.first_name || item.name || '-'}} @{{item.last_name || ''}}</td>
                     <td>@{{item.dob || '-'}}</td>
                     <td>@{{item.gender || '-'}}</td>
                     <td>@{{item.mobile || '-'}}</td>
                     <td>@{{item.email || '-'}}</td>
                     <td>
                        <span class="badge" ng-class="item.active == 0 ? 'text-bg-danger' : 'text-bg-success'">
                           @{{item.active == 0 ? 'Inactive' : 'Active'}}
                        </span>
                     </td>
                     <td>
                        <a ng-if="item.enc_id" ng-href="@{{baseUrl + '/admin/teachers/add/' + encodeURIComponent(item.enc_id)}}" class="btn btn-sm btn-outline-primary">
                           <i class="ri-edit-2-line"></i> Edit
                        </a>
                     </td>
                  </tr>
                  <tr ng-if="!teachers.length">
                     <td colspan="8" class="text-center py-4 text-secondary">No teachers found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
@endsection
