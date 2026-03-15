@extends('layout.layout')

@section('main')
<div ng-controller="addStudentCtrl" ng-init="init('{{ $student_token }}');" >
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h4 class="fw-bold text-lg">@{{formData.enc_id ? 'Edit Student' : 'Add Student'}}</h4>
      <a href="{{ url('admin/students') }}" class="border border-danger-600 bg-hover-danger-200 btn-sm text-danger-600 text-md px-50 py-11 radius-8">Back to List</a>
   </div>

   <div class="card">
      <div class="card-body">
         <form name="myForm" novalidate="novalidate" ng-submit="submit(myForm.$valid)">
            <div class="row g-3">
               <div class="form-group col-md-4">
                  <label class="form-label">Admission No</label>
                  <input type="text" class="form-control" ng-model="formData.admission_no">
               </div>
               <div class="form-group col-md-4">
                  <label class="form-label">First Name *</label>
                  <input type="text" class="form-control" ng-model="formData.first_name" required>
               </div>
               <div class="form-group col-md-4">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" ng-model="formData.last_name">
               </div>

               <div class="form-group col-md-4">
                  <label class="form-label">Date of Birth</label>
                  <!-- <input type="date" class="form-control" ng-model="formData.dob" required> -->

                  <input class="flatpickr flatpickr-input form-control" type="text" ng-model="formData.dob" placeholder="Select Date.."  required>
               </div>
               <div class="form-group col-md-4">
                  <label class="form-label">Gender</label>
                  <select class="form-select" ng-model="formData.gender" required>
                     <option value="">Select</option>
                     <option value="Male">Male</option>
                     <option value="Female">Female</option>
                     <option value="Other">Other</option>
                  </select>
               </div>
               <div class="form-group col-md-4">
                  <label class="form-label">Mobile</label>
                  <input type="number" class="form-control" ng-model="formData.mobile" required >
               </div>

               <div class="form-group col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" ng-model="formData.email" required>
               </div>
               <div class="form-group col-md-6">
                  <label class="form-label">Aadhar No</label>
                  <input type="number" class="form-control" ng-model="formData.aadhar_no" required >
               </div>

               <div class="form-group col-md-12">
                  <label class="form-label">Residential Address</label>
                  <textarea class="form-control" rows="2" ng-model="formData.residential_address" required></textarea>
               </div>

               <div class="form-group col-md-12">
                  <label class="form-label">Permanent Address</label>
                  <textarea class="form-control" rows="2" ng-model="formData.permanent_address" required></textarea>
               </div>

               <div class="col-12 mt-16">
                 
                  <h6 class="mb-2 fw-bold text-lg">Parent Details</h6>
               </div>

               <div class="form-group col-md-6">
                  <label class="form-label">Father's Name</label>
                  <input type="text" class="form-control" ng-model="formData.father_name" required>
               </div>
               <div class="form-group col-md-6">
                  <label class="form-label">Father's Mobile</label>
                  <input type="text" class="form-control" ng-model="formData.father_mobile" required >
               </div>
               <div class="form-group col-md-6">
                  <label class="form-label">Father's Email</label>
                  <input type="email" class="form-control" ng-model="formData.father_email">
               </div>

               
               <div class="form-group col-md-6">
                  <label class="form-label">Father's Aadhar No</label>
                  <input type="number" class="form-control" ng-model="formData.father_aadhar_no" required >
               </div>

               <div class="form-group col-md-6">
                  <label class="form-label">Mother's Name</label>
                  <input type="text" class="form-control" ng-model="formData.mother_name" required>
               </div>
               <div class="form-group col-md-6">
                  <label class="form-label">Mother's Mobile</label>
                  <input type="text" class="form-control" ng-model="formData.mother_mobile"  >
               </div>
               <div class="form-group col-md-6">
                  <label class="form-label">Mother's Email</label>
                  <input type="email" class="form-control" ng-model="formData.mother_email">
               </div>

               
               <div class="form-group col-md-6">
                  <label class="form-label">Mother's Aadhar No</label>
                  <input type="text" class="form-control" ng-model="formData.mother_aadhar_no" required >
               </div>

               <div class="form-group col-md-4">
                  <label class="form-label">Status</label>
                  <select class="form-select" ng-model="formData.active">
                     <option value="1">Active</option>
                     <option value="0">Inactive</option>
                  </select>
               </div>
            </div>

            <div class="d-flex mt-16">
               <button type="submit" class="btn btn-primary-600 d-flex align-items-center gap-6" ng-disabled="processing">
                  @{{processing ? 'Saving...' : (formData.enc_id ? 'Update Student' : 'Save Student')}}
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
