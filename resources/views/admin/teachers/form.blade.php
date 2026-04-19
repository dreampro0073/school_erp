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

   <form name="myForm" class="teacher-form" novalidate="novalidate" ng-submit="submit()">
      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
             <h6 class="text-lg fw-semibold mb-0">Personal Info</h6>
         </div>
         <div class="card-body p-20">
            <div class="row g-3">
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">First Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" name="first_name" ng-model="formData.first_name" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.first_name.$touched || myForm.$submitted) && myForm.first_name.$error.required">
                     First name is required.
                  </div>
               </div>

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Last Name</label>
                  <input type="text" class="form-control" ng-model="formData.last_name">
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Date of Birth <span class="text-danger-600">* </span></label>
                  <input type="date" class="form-control" name="dob" ng-model="formData.dob" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.dob.$touched || myForm.$submitted) && myForm.dob.$error.required">
                     Date of birth is required.
                  </div>
               </div>

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Gender <span class="text-danger-600">* </span></label>
                  <select class="form-select" name="gender" ng-model="formData.gender" required>
                     <option value="">Select</option>
                     <option value="Male">Male</option>
                     <option value="Female">Female</option>
                     <option value="Other">Other</option>
                  </select>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.gender.$touched || myForm.$submitted) && myForm.gender.$error.required">
                     Gender is required.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mobile <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" name="mobile" ng-model="formData.mobile" ng-pattern="/^[0-9]{10}$/" pattern="[0-9]{10}" maxlength="10" inputmode="numeric" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.mobile.$touched || myForm.$submitted) && myForm.mobile.$error.required">
                     Mobile number is required.
                  </div>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.mobile.$touched || myForm.$submitted) && myForm.mobile.$error.pattern">
                     Enter a valid 10-digit mobile number.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Email <span class="text-danger-600">* </span></label>
                  <input type="email" class="form-control" name="email" ng-model="formData.email" ng-readonly="formData.enc_id">
               </div>
               <!-- <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Email <span class="text-danger-600">* </span></label>
                  <input type="email" class="form-control" name="email" ng-model="formData.email">
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.email.$touched || myForm.$submitted) && myForm.email.$error.required">
                     Email is required.
                  </div>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.email.$touched || myForm.$submitted) && myForm.email.$error.email">
                     Enter a valid email address.
                  </div>
               </div> -->

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Aadhar No  <span class="text-danger-600">* </span> </label>
                  <input type="text" class="form-control" name="aadhar_no" ng-model="formData.aadhar_no" ng-pattern="/^[0-9]{12}$/" pattern="[0-9]{12}" maxlength="12" inputmode="numeric" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.aadhar_no.$touched || myForm.$submitted) && myForm.aadhar_no.$error.required">
                     Aadhar number is required.
                  </div>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.aadhar_no.$touched || myForm.$submitted) && myForm.aadhar_no.$error.pattern">
                     Enter a valid 12-digit Aadhar number.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                     Marital Status <span class="text-danger-600">*</span>
                  </label>
                  <select class="form-select" name="marital_status" ng-model="formData.marital_status" required>
                     <option value="">Select</option>
                     <option value="Married">Married</option>
                     <option value="Unmarried">Unmarried</option>
                     <option value="Divorced">Divorced</option>
                     <option value="Widowed">Widowed</option>
                  </select>
                  <div class="text-danger-600 text-xs mt-1" 
                       ng-if="(myForm.marital_status.$touched || myForm.$submitted) && myForm.marital_status.$error.required">
                     Marital status is required.
                  </div>
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
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Name </label>
                  <input type="text" class="form-control" ng-model="formData.father_name">
               </div>
               @if(Auth::user()->client_id == 2)
               <div class="form-group col-md-6">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Mobile </label>
                  <input type="text" class="form-control" ng-model="formData.father_mobile">
               </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Email </label>
                     <input type="email" class="form-control" ng-model="formData.father_email" ng-readonly="formData.enc_id">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Aadhar No </label>
                     <input type="text" class="form-control" ng-model="formData.father_aadhar_no">
                 </div>
                 @endif

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Name </label>
                     <input type="text" class="form-control" ng-model="formData.mother_name">
                 </div>

                 @if(Auth::user()->client_id == 2)
                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Mobile</label>
                     <input type="text" class="form-control" ng-model="formData.mother_mobile">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Email </label>
                     <input type="email" class="form-control" ng-model="formData.mother_email">
                 </div>

                 <div class="form-group col-md-6">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Aadhar No </label>
                     <input type="text" class="form-control" ng-model="formData.mother_aadhar_no">
                 </div>
                 @endif

                 <div class="form-group col-md-4">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Status </label>
                     <select class="form-select" ng-model="formData.active">
                         <option value="0">Active</option>
                         <option value="1">Inactive</option>
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
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Blood Group <span class="text-danger-600">* </span> </label>
                     <select class="form-select" name="blood_group_id" ng-model="formData.blood_group_id" convert-to-number required>
                         <option value="">Select</option>
                         <option value="@{{item.value}}" ng-repeat="item in blood_groups">@{{item.label}}</option>
                     </select>
                     <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.blood_group_id.$touched || myForm.$submitted) && myForm.blood_group_id.$error.required">
                        Blood group is required.
                     </div>
                 </div>

                 <div class="form-group col-md-4">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Weight <span class="text-danger-600">* </span> </label>
                     <input type="text" class="form-control" name="weight" ng-model="formData.weight" required>
                     <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.weight.$touched || myForm.$submitted) && myForm.weight.$error.required">
                        Weight is required.
                     </div>
                 </div>
                 <div class="form-group col-md-4">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Height  <span class="text-danger-600">* </span> </label>
                     <input type="text" class="form-control" name="height" ng-model="formData.height" required>
                     <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.height.$touched || myForm.$submitted) && myForm.height.$error.required">
                        Height is required.
                     </div>
                 </div>
             </div>
         </div>
      </div>

      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="text-lg fw-semibold mb-0">Professional Details</h6>
         </div>

         <div class="card-body p-20">
            <div class="row g-3">

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light mb-8">
                     Qualification <span class="text-danger-600">*</span>
                  </label>
                  <select class="form-select" ng-model="formData.qualification" required>
                     <option value="">Select</option>
                     <option value="D.El.Ed">D.El.Ed</option>
                     <option value="B.Ed">B.Ed</option>
                     <option value="M.Ed">M.Ed</option>
                     <option value="Graduation">Graduation</option>
                     <option value="Post Graduation">Post Graduation</option>
                     <option value="PhD">PhD</option>
                  </select>
               </div>

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light mb-8">
                     Teaching Eligibility
                  </label>
                  <select class="form-select" ng-model="formData.eligibility">
                     <option value="">Select</option>
                     <option value="CTET">CTET</option>
                     <option value="TET">State TET</option>
                     <option value="NET">UGC NET</option>
                     <option value="None">None</option>
                  </select>
               </div>

               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light mb-8">
                     Experience (Years)
                  </label>
                  <input type="number" class="form-control" ng-model="formData.experience">
               </div>

               <div class="form-group col-md-6">
                  <label class="text-sm fw-semibold text-primary-light mb-8">
                     Skills
                  </label>
                  <input type="text" class="form-control" placeholder="e.g. Classroom Management, MS Office" ng-model="formData.skills">
               </div>

               <div class="form-group col-md-6">
                  <label class="text-sm fw-semibold text-primary-light mb-8">
                     Previous School Name
                  </label>
                  <input type="text" class="form-control" ng-model="formData.previous_school">
               </div>

               <div class="form-group col-md-6">
                  <label class="text-sm fw-semibold text-primary-light mb-8">
                     Previous School Address
                  </label>
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
                     <textarea class="form-control" rows="1" name="residential_address" ng-model="formData.residential_address" required></textarea>
                     <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.residential_address.$touched || myForm.$submitted) && myForm.residential_address.$error.required">
                        Residential address is required.
                     </div>
                  </div>

                  <div class="form-group col-md-12">
                     <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Permanent Address  <span class="text-danger-600">* </span> </label>
                     <textarea class="form-control" rows="1" name="permanent_address" ng-model="formData.permanent_address" required></textarea>
                     <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.permanent_address.$touched || myForm.$submitted) && myForm.permanent_address.$error.required">
                        Permanent address is required.
                     </div>
                  </div> 
             </div>
         </div>
      </div>

      @if(Auth::user()->client_id == 2)
      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-lg fw-semibold mb-0">Salary Structure</h6>
         </div>

         <div class="card-body p-20">
            <div class="row g-3">
               <div class="col-12" ng-repeat="item in formData.salary_components track by $index">
                  <div class="row g-2 align-items-stretch">
                    <div class="form-group col-md-5">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Component Name <span class="text-danger-600">* </span></label>
                        <input type="text" class="form-control" name="component_name_@{{$index}}" ng-model="item.component_name" placeholder="Basic Salary / HRA / PF" required>
                        <div class="text-danger-600 text-xs mt-1" ng-if="myForm['component_name_' + $index] && (myForm['component_name_' + $index].$touched || myForm.$submitted) && myForm['component_name_' + $index].$error.required">
                           Component name is required.
                        </div>
                     </div>
                     <div class="form-group col-md-3">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Type <span class="text-danger-600">* </span></label>
                        <select class="form-select" name="component_type_@{{$index}}" ng-model="item.component_type" required>
                           <option value="earning">Earning</option>
                           <option value="deduction">Deduction</option>
                        </select>
                        <div class="text-danger-600 text-xs mt-1" ng-if="myForm['component_type_' + $index] && (myForm['component_type_' + $index].$touched || myForm.$submitted) && myForm['component_type_' + $index].$error.required">
                           Component type is required.
                        </div>
                     </div>
                     <div class="form-group col-md-3 salary-amount-group">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Amount <span class="text-danger-600">* </span></label>

                        <input type="text"
                         class="form-control salary-amount-input"
                         name="component_amount_@{{$index}}"
                         ng-model="item.amount"
                         ng-pattern="/^\d+(\.\d{1,2})?$/"
                         pattern="[0-9]+(\.[0-9]{1,2})?"
                         placeholder="e.g. 22.50"
                         inputmode="decimal"
                         ng-class="{'is-invalid': myForm['component_amount_' + $index] && (myForm['component_amount_' + $index].$touched || myForm.$submitted) && myForm['component_amount_' + $index].$invalid}"
                         required>
                        <div class="salary-amount-feedback" aria-live="polite">
                           <div class="salary-amount-error" ng-if="myForm['component_amount_' + $index] && (myForm['component_amount_' + $index].$touched || myForm.$submitted) && myForm['component_amount_' + $index].$error.required">
                              Amount is required.
                           </div>
                           <div class="salary-amount-error" ng-if="myForm['component_amount_' + $index] && (myForm['component_amount_' + $index].$touched || myForm.$submitted) && myForm['component_amount_' + $index].$error.pattern">
                              Enter a valid amount.
                           </div>
                        </div>
                     </div>
                     <div class="form-group col-md-1 d-flex align-items-end justify-content-center">
                        <button type="button" class="btn btn-outline-danger" ng-click="removeSalaryComponent($index)" ng-disabled="formData.salary_components.length === 1" title="Remove">
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
      @endif

      <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
         <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-lg fw-semibold mb-0">Bank Details</h6>
         </div>

         <div class="card-body p-20">
             <div class="row g-3">
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Account Holder Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" name="bank_account_holder_name" ng-model="formData.bank_details.account_holder_name" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.bank_account_holder_name.$touched || myForm.$submitted) && myForm.bank_account_holder_name.$error.required">
                     Account holder name is required.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Bank Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" name="bank_name" ng-model="formData.bank_details.bank_name" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.bank_name.$touched || myForm.$submitted) && myForm.bank_name.$error.required">
                     Bank name is required.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Account Number <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" name="bank_account_number" ng-model="formData.bank_details.account_number" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.bank_account_number.$touched || myForm.$submitted) && myForm.bank_account_number.$error.required">
                     Account number is required.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">IFSC Code <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control text-uppercase" name="bank_ifsc_code" ng-model="formData.bank_details.ifsc_code" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.bank_ifsc_code.$touched || myForm.$submitted) && myForm.bank_ifsc_code.$error.required">
                     IFSC code is required.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Branch Name <span class="text-danger-600">* </span></label>
                  <input type="text" class="form-control" name="bank_branch_name" ng-model="formData.bank_details.branch_name" required>
                  <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.bank_branch_name.$touched || myForm.$submitted) && myForm.bank_branch_name.$error.required">
                     Branch name is required.
                  </div>
               </div>
               <div class="form-group col-md-4">
                  <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">UPI ID</label>
                  <input type="text" class="form-control" ng-model="formData.bank_details.upi_id">
               </div>
            </div>
         </div>
      </div>

      <div class="d-flex align-items-center justify-content-center gap-3 mt-16 mb-24">
         <a href="{{url('admin/teachers')}}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-50 py-11 radius-8">
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
