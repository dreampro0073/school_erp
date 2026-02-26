@extends('admin.layout')

@section('main')
<div ng-controller="subjectsCtrl" ng-init="init();">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0">Subjects</h6>
         <p class="text-neutral-600 mt-4 mb-0">Manage subjects list.</p>
      </div>
      <div class="d-flex align-items-center gap-2">
         <button type="button" class="btn btn-success-600 d-inline-flex align-items-center gap-2" ng-click="openAddModal()">
            <i class="ri-add-line"></i>
            Add Subject
         </button>
         <a href="{{ url('/super-admin/dashboard') }}" class="btn btn-primary-600 d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Back
         </a>
      </div>
   </div>

   <div class="card">
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Subject</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="item in subjects track by item.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{item.name}}</td>
                     <td>
                        <span ng-if="item.active == 1" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                        <span ng-if="item.active != 1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                     </td>
                     <td>
                        <button type="button" class="btn btn-sm btn-info-100 text-info-600 me-8" ng-click="openEditModal(item)">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger-100 text-danger-600" ng-click="deleteSubject(item)">Delete</button>
                     </td>
                  </tr>
                  <tr ng-if="!subjects.length">
                     <td colspan="4" class="text-center py-4">No subjects found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <div class="modal fade" id="subjectModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">@{{ formData.id ? 'Edit Subject' : 'Add Subject' }}</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form ng-submit="saveSubject()">
               <div class="modal-body">
                  <div class="mb-16">
                     <label class="form-label">Subject Name</label>
                     <input type="text" class="form-control" ng-model="formData.name" required>
                  </div>
                  <div>
                     <label class="form-label">Status</label>
                     <select class="form-control" ng-model="formData.active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                     </select>
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

