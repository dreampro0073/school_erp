@extends('layout.layout')

@section('main')
<div ng-controller="studentCtrl" ng-init="init();" class="mt-24">
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h5 class="mb-0">Students</h5>
      <a href="{{ route('admin.students.add') }}" class="btn btn-primary">
         <i class="ri-add-line"></i> Add Student
      </a>
   </div>

   <div class="card h-100">
      <div class="card-header">
         <div class="row g-2">
            <div class="col-md-5">
               <input
                  type="text"
                  class="form-control"
                  placeholder="Search by name, admission no, mobile, email"
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
                     <th>Admission No</th>
                     <th>Name</th>
                     <th>DOB</th>
                     <th>Gender</th>
                     <th>Mobile</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="item in students track by item.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{item.admission_no || '-'}}</td>
                     <td>
                        <a ng-if="item.enc_id" ng-href="@{{baseUrl + '/admin/students/profile/' + encodeURIComponent(item.enc_id)}}" class="text-primary fw-semibold">
                           @{{item.first_name || item.name || '-'}}
                        </a>
                        <span ng-if="!item.enc_id">@{{item.first_name || item.name || '-'}}</span>
                     </td>
                     <td>@{{item.dob || '-'}}</td>
                     <td>@{{item.gender || '-'}}</td>
                     <td>@{{item.mobile || '-'}}</td>
                     <td>
                        <span class="badge" ng-class="item.active == 0 ? 'text-bg-danger' : 'text-bg-success'">
                           @{{item.active == 0 ? 'Inactive' : 'Active'}}
                        </span>
                     </td>
                     <td>
                        <div class="d-flex gap-2">
                           <a ng-if="item.enc_id" ng-href="@{{baseUrl + '/admin/students/add/' + encodeURIComponent(item.enc_id)}}" class="btn btn-sm btn-outline-primary">
                              <i class="ri-edit-2-line"></i> Edit
                           </a>
                           <button
                              type="button"
                              class="btn btn-sm"
                              ng-class="item.active == 0 ? 'btn-success' : 'btn-warning'"
                              ng-click="toggleStatus(item, item.active == 0 ? 1 : 0)">
                              @{{item.active == 0 ? 'Activate' : 'Deactivate'}}
                           </button>
                        </div>
                     </td>
                  </tr>
                  <tr ng-if="!students.length">
                     <td colspan="8" class="text-center py-4 text-secondary">No students found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
@endsection
