@extends('layout.layout')

@section('main')
<div ng-controller="addTeacherCtrl" ng-init="init('{{ $teacher_token }}');" class="mt-24">
 
   <div class="d-flex justify-content-between align-items-center mb-16">
        <h4 class="fw-bold text-lg">
            @{{formData.enc_id ? 'Edit Teacher' : 'Add Teacher'}}
        </h4>
        <a href="{{ url('admin/teachers') }}"
           class="border border-danger-600 bg-hover-danger-200 btn-sm text-danger-600 text-md px-50 py-11 radius-8">
            Back to List
        </a>
    </div>

   <form name="myForm" novalidate="novalidate" ng-submit="submit(myForm.$valid)">
      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
             <h6 class="text-lg fw-semibold mb-0">Personal Info</h6>
         </div>
         <div class="card-body p-20">
            <div class="row g-3">
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">First Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" ng-model="formData.first_name" required>
               </div>

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Last Name</label>
                  <input type="text" class="form-control" ng-model="formData.last_name">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Date of Birth <span class="text-danger-600">* </span></label>
                  <input type="date" class="form-control" ng-model="formData.dob">
               </div>

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Gender <span class="text-danger-600">* </span></label>
                  <select class="form-control" ng-model="formData.gender">
                     <option value="">Select</option>
                     <option value="Male">Male</option>
                     <option value="Female">Female</option>
                     <option value="Other">Other</option>
                  </select>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mobile <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" ng-model="formData.mobile">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Email <span class="text-danger-600">* </span></label>
                  <input type="email" class="form-control" ng-model="formData.email">
               </div>

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Aadhar No  <span class="text-danger-600">* </span> </label>
                  <input type="text" class="form-control" ng-model="formData.aadhar_no">
              </div>
            </div>
         </div>
      </div>
              
      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
             <h6 class="text-lg fw-semibold mb-0">Parent & Guardian Info</h6>
         </div>
         <div class="card-body p-20">
            <div class="row g-3">
               <div class="form-group col-md-6">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Name  <span class="text-danger-600">* </span> </label>
                  <input type="text" class="form-control" ng-model="formData.father_name">
               </div>

               <div class="form-group col-md-6">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Mobile </label>
                  <input type="text" class="form-control" ng-model="formData.father_mobile">
               </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Email </label>
                     <input type="email" class="form-control" ng-model="formData.father_email" ng-readonly="formData.id">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Aadhar No  <span class="text-danger-600">* </span> </label>
                     <input type="text" class="form-control" ng-model="formData.father_aadhar_no">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Name  <span class="text-danger-600">* </span> </label>
                     <input type="text" class="form-control" ng-model="formData.mother_name">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Mobile </span> </label>
                     <input type="text" class="form-control" ng-model="formData.mother_mobile">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Email </label>
                     <input type="email" class="form-control" ng-model="formData.mother_email">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Aadhar No  <span class="text-danger-600">* </span> </label>
                     <input type="text" class="form-control" ng-model="formData.mother_aadhar_no">
                 </div>

                 <div class="form-group col-md-4">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Status </label>
                     <select class="form-select" ng-model="formData.active">
                         <option value="1">Active</option>
                         <option value="0">Inactive</option>
                     </select>
                 </div>

             </div>
         </div>
      </div>

      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-lg fw-semibold mb-0">Medical Info</h6>
         </div>
         <div class="card-body p-20">
             <div class="row g-3">
                 <div class="form-group col-md-4">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Relgion <span class="text-danger-600">* </span> </label>
                     <select class="form-select" ng-model="formData.blood_group_id" convert-to-number>
                         <option value="">Select</option>
                         <option value="@{{item.value}}" ng-repeat="item in blood_groups">@{{item.label}}</option>
                     </select>
                 </div>

                 <div class="form-group col-md-4">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Weight <span class="text-danger-600">* </span> </label>
                     <input type="text" class="form-control" ng-model="formData.weight">
                 </div>
                 <div class="form-group col-md-4">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Height  <span class="text-danger-600">* </span> </label>
                     <input type="text" class="form-control" ng-model="formData.height">
                 </div>
             </div>
         </div>
      </div>

      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
             <h6 class="text-lg fw-semibold mb-0">Previous School Details</h6>
         </div>
         <div class="card-body p-20">
             <div class="row g-3">
                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">School Name </label>
                     <input type="text" class="form-control" ng-model="formData.previous_school">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Address  </label>
                     <input type="text" class="form-control" ng-model="formData.previous_school_address">
                 </div>
                 
             </div>
         </div>
      </div>

      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
             <h6 class="text-lg fw-semibold mb-0">Address Details</h6>
         </div>
         <div class="card-body p-20">
             <div class="row g-3">
                  <div class="form-group col-md-12">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Residential Address  <span class="text-danger-600">* </span> </label>
                     <textarea class="form-control" rows="1" ng-model="formData.residential_address"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Permanent Address  <span class="text-danger-600">* </span> </label>
                     <textarea class="form-control" rows="1" ng-model="formData.permanent_address"></textarea>
                  </div> 
             </div>
         </div>
      </div>

      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-lg fw-semibold mb-0">Salary Structure</h6>
         </div>

         <div class="card-body p-20">
            <div class="row g-3">
               <div class="col-12" ng-repeat="item in formData.salary_components track by $index">
                  <div class="row g-2 align-items-end">
                     <div class="form-group col-md-5">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Component Name <span class="text-danger-600">* </span></label>
                        <input type="text" class="form-control" ng-model="item.component_name" placeholder="Basic Salary / HRA / PF" required>
                     </div>
                     <div class="form-group col-md-3">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Type <span class="text-danger-600">* </span></label>
                        <select class="form-control" ng-model="item.component_type">
                           <option value="earning">Earning</option>
                           <option value="deduction">Deduction</option>
                        </select>
                     </div>
                     <div class="form-group col-md-3">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Amount <span class="text-danger-600">* </span></label>

                        <input type="text"
                         class="form-control"
                         ng-model="item.amount"
                         ng-pattern="/^\d+(\.\d{1,2})?$/"
                         placeholder="e.g. 22.50"
                         required>
                     </div>
                     <div class="form-group col-md-1 d-grid">
                        <button type="button" class="btn btn-outline-danger" ng-click="removeSalaryComponent($index)" title="Remove">
                           <i class="ri-delete-bin-line"></i>
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="py-20">
               <div class="col-12 d-flex justify-content-between align-items-center">
                  <button type="button" class="border border-success-600 bg-hover-success-200 text-success-600 text-md px-50 py-6 radius-8" ng-click="addSalaryComponent()">
                     <i class="ri-add-line"></i> Add Component
                  </button>
                  <div class="text-end">
                     <div class="text-sm">Earnings: <strong>@{{ totalEarning() | number:2 }}</strong></div>
                     <div class="text-sm">Deductions: <strong>@{{ totalDeduction() | number:2 }}</strong></div>
                     <div>Net Salary: <strong>@{{ totalNet() | number:2 }}</strong></div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-lg fw-semibold mb-0">Bank Details</h6>
         </div>

         <div class="card-body p-20">
             <div class="row g-3">
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Account Holder Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.account_holder_name">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Bank Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.bank_name">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Account Number <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.account_number">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">IFSC Code <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control text-uppercase" ng-model="formData.bank_details.ifsc_code">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Branch Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.branch_name">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">UPI ID</label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.upi_id">
               </div>
            </div>
         </div>
      </div>

      <div class="d-flex align-items-center justify-content-center gap-3 mt-16 mb-24">
         <a href="{{url('admin/teachers/add')}}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-50 py-11 radius-8">
            Cancel
         </a>
         <button type="submit"
             class="btn btn-primary-600 border border-primary-600 text-md px-28 py-12 radius-8"
                 ng-disabled="processing">
             @{{processing ? 'Saving...' : 'Save Changes'}}
         </button>
      </div>
   </form>
</div>
@endsection
