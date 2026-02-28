@extends('admin.layout')

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
