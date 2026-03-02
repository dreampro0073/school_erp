@extends('layout.layout')

@section('main')
<div ng-controller="addTeacherCtrl" ng-init="init('{{ $teacherToken }}');" class="mt-24">
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h5 class="mb-0">@{{formData.enc_id ? 'Edit Teacher' : 'Add Teacher'}}</h5>
      <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Back to List</a>
   </div>

   <div class="card">
      <div class="card-body">
         <form ng-submit="submit()">
            <div class="row g-3">
               <div class="col-md-4">
                  <label class="form-label">First Name *</label>
                  <input type="text" class="form-control" ng-model="formData.first_name" required>
               </div>
               <div class="col-md-4">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" ng-model="formData.last_name">
               </div>
               <div class="col-md-4">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" class="form-control" ng-model="formData.dob">
               </div>

               <div class="col-md-4">
                  <label class="form-label">Gender</label>
                  <select class="form-control" ng-model="formData.gender">
                     <option value="">Select</option>
                     <option value="Male">Male</option>
                     <option value="Female">Female</option>
                     <option value="Other">Other</option>
                  </select>
               </div>
               <div class="col-md-4">
                  <label class="form-label">Mobile</label>
                  <input type="text" class="form-control" ng-model="formData.mobile">
               </div>
               <div class="col-md-4">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" ng-model="formData.email">
               </div>

               <div class="col-md-6">
                  <label class="form-label">Aadhar No</label>
                  <input type="text" class="form-control" ng-model="formData.aadhar_no">
               </div>
               <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select class="form-control" ng-model="formData.active">
                     <option value="1">Active</option>
                     <option value="0">Inactive</option>
                  </select>
               </div>

               <div class="col-md-12">
                  <label class="form-label">Address</label>
                  <textarea class="form-control" rows="2" ng-model="formData.address"></textarea>
               </div>

               <div class="col-md-6">
                  <label class="form-label">Document Type</label>
                  <input type="text" class="form-control" ng-model="formData.document_type" placeholder="Aadhar / Certificate">
               </div>
               <div class="col-md-6">
                  <label class="form-label">Document No</label>
                  <input type="text" class="form-control" ng-model="formData.document_no">
               </div>

               <div class="col-12 mt-3">
                  <hr>
                  <h6 class="mb-0">Salary Structure</h6>
                  <small class="text-secondary">Define monthly earning and deduction components for this teacher.</small>
               </div>

               <div class="col-12" ng-repeat="item in formData.salary_components track by $index">
                  <div class="row g-2 align-items-end">
                     <div class="col-md-5">
                        <label class="form-label">Component Name *</label>
                        <input type="text" class="form-control" ng-model="item.component_name" placeholder="Basic Salary / HRA / PF" required>
                     </div>
                     <div class="col-md-3">
                        <label class="form-label">Type *</label>
                        <select class="form-control" ng-model="item.component_type">
                           <option value="earning">Earning</option>
                           <option value="deduction">Deduction</option>
                        </select>
                     </div>
                     <div class="col-md-3">
                        <label class="form-label">Amount *</label>
                        <input type="number" step="0.01" min="0" class="form-control" ng-model="item.amount" required>
                     </div>
                     <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-outline-danger" ng-click="removeSalaryComponent($index)" title="Remove">
                           <i class="ri-delete-bin-line"></i>
                        </button>
                     </div>
                  </div>
               </div>

               <div class="col-12 d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-outline-primary btn-sm" ng-click="addSalaryComponent()">
                     <i class="ri-add-line"></i> Add Component
                  </button>
                  <div class="text-end">
                     <div class="text-sm">Earnings: <strong>@{{ totalEarning() | number:2 }}</strong></div>
                     <div class="text-sm">Deductions: <strong>@{{ totalDeduction() | number:2 }}</strong></div>
                     <div>Net Salary: <strong>@{{ totalNet() | number:2 }}</strong></div>
                  </div>
               </div>

               <div class="col-12 mt-3">
                  <hr>
                  <h6 class="mb-0">Bank Details</h6>
                  <small class="text-secondary">If any bank field is entered, required fields must be filled.</small>
               </div>

               <div class="col-md-6">
                  <label class="form-label">Account Holder Name *</label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.account_holder_name">
               </div>
               <div class="col-md-6">
                  <label class="form-label">Bank Name *</label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.bank_name">
               </div>
               <div class="col-md-6">
                  <label class="form-label">Account Number *</label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.account_number">
               </div>
               <div class="col-md-6">
                  <label class="form-label">IFSC Code *</label>
                  <input type="text" class="form-control text-uppercase" ng-model="formData.bank_details.ifsc_code">
               </div>
               <div class="col-md-6">
                  <label class="form-label">Branch Name</label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.branch_name">
               </div>
               <div class="col-md-6">
                  <label class="form-label">UPI ID</label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.upi_id">
               </div>
            </div>

            <div class="mt-4 d-flex gap-2">
               <button type="submit" class="btn btn-primary" ng-disabled="processing">
                  @{{processing ? 'Saving...' : (formData.enc_id ? 'Update Teacher' : 'Save Teacher')}}
               </button>
               <a href="{{ route('admin.teachers.index') }}" class="btn btn-light">Cancel</a>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
