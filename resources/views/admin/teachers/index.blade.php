@extends('layout.layout')

@section('main')
<div ng-controller="teacherCtrl" ng-init="init();" class="mt-24">
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h5 class="mb-0">Teachers</h5>
      <a href="{{ url('/admin/teachers/add') }}" class="btn btn-primary">
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
                  <tr ng-repeat="item in teachers track by $index">
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
                        @if(false)
                       <!--  <a ng-if="item.unique_id" ng-href="@{{baseUrl + '/admin/teachers/add/' + encodeURIComponent(item.unique_id)}}" class="btn btn-sm btn-outline-primary">
                           <i class="ri-edit-2-line"></i> Edit
                        </a> -->
                        @endif

                        <a 
                          ng-if="item.unique_id"
                          ng-href="@{{ baseUrl + '/admin/teachers/add/' + item.unique_id }}"
                          class="btn btn-sm btn-outline-primary">
                          Edit
                        </a>
                     </td>
                  </tr>
                  <tr ng-if="!teachers.length ">
                     <td colspan="8" class="text-center py-4 text-secondary">No teachers found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

   @if(false)
   <div class="card h-100 mt-16">
      <div class="card-header">
         <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="mb-0">Teacher Salary Logs</h6>
            <button type="button" class="btn btn-outline-secondary btn-sm" ng-click="loadSalaryLogs()">
               Refresh
            </button>
         </div>
      </div>
      <div class="card-body">
         <div class="row g-2 mb-3">
            <div class="col-md-4">
               <select class="form-select" ng-model="salaryFilters.teacher_id">
                  <option value="">All Teachers</option>
                  <option ng-repeat="opt in teacherOptions track by opt.teacher_id" ng-value="opt.teacher_id">
                     @{{ opt.teacher_name }}
                  </option>
               </select>
            </div>
            <div class="col-md-3">
               <input type="month" class="form-control" ng-model="salaryFilters.month">
            </div>
            <div class="col-md-2">
               <button type="button" class="btn btn-primary w-100" ng-click="loadSalaryLogs()">Apply</button>
            </div>
            <div class="col-md-2">
               <button type="button" class="btn btn-light w-100" ng-click="resetSalaryFilters()">Clear</button>
            </div>
         </div>

         <div class="border rounded p-3 mb-3">
            <h6 class="mb-3">Add / Update Salary Payment</h6>
            <div class="row g-2">
               <div class="col-md-3">
                  <label class="form-label">Teacher *</label>
                  <select class="form-select" ng-model="logForm.teacher_id">
                     <option value="">Select Teacher</option>
                     <option ng-repeat="opt in teacherOptions track by opt.teacher_id" ng-value="opt.teacher_id">
                        @{{ opt.teacher_name }}
                     </option>
                  </select>
               </div>
               <div class="col-md-2">
                  <label class="form-label">Salary Month *</label>
                  <input type="month" class="form-control" ng-model="logForm.salary_month">
               </div>
               <div class="col-md-2">
                  <label class="form-label">Gross *</label>
                  <input type="number" step="0.01" min="0" class="form-control" ng-model="logForm.gross_amount">
               </div>
               <div class="col-md-2">
                  <label class="form-label">Deduction</label>
                  <input type="number" step="0.01" min="0" class="form-control" ng-model="logForm.deduction_amount">
               </div>
               <div class="col-md-3">
                  <label class="form-label">Payment Date</label>
                  <input type="date" class="form-control" ng-model="logForm.payment_date">
               </div>
               <div class="col-md-3">
                  <label class="form-label">Payment Mode</label>
                  <input type="text" class="form-control" ng-model="logForm.payment_mode" placeholder="Cash / Bank / UPI">
               </div>
               <div class="col-md-3">
                  <label class="form-label">Transaction Ref</label>
                  <input type="text" class="form-control" ng-model="logForm.transaction_ref">
               </div>
               <div class="col-md-4">
                  <label class="form-label">Remark</label>
                  <input type="text" class="form-control" ng-model="logForm.remark">
               </div>
               <div class="col-md-2 d-grid align-items-end">
                  <button type="button" class="btn btn-success" ng-click="saveSalaryLog()" ng-disabled="logProcessing">
                     @{{ logProcessing ? 'Saving...' : 'Save Log' }}
                  </button>
               </div>
            </div>
         </div>

         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Teacher</th>
                     <th>Month</th>
                     <th>Gross</th>
                     <th>Deduction</th>
                     <th>Net</th>
                     <th>Payment Date</th>
                     <th>Mode</th>
                     <th>Ref</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="log in salaryLogs track by log.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{log.teacher_name || '-'}}</td>
                     <td>@{{log.salary_month_label || log.salary_month || '-'}}</td>
                     <td>@{{log.gross_amount || 0}}</td>
                     <td>@{{log.deduction_amount || 0}}</td>
                     <td><strong>@{{log.net_amount || 0}}</strong></td>
                     <td>@{{log.payment_date || '-'}}</td>
                     <td>@{{log.payment_mode || '-'}}</td>
                     <td>@{{log.transaction_ref || '-'}}</td>
                  </tr>
                  <tr ng-if="!salaryLogs.length">
                     <td colspan="9" class="text-center py-4 text-secondary">No salary logs found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   @endif
</div>
@endsection
