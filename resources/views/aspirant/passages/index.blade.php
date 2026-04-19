@extends('layout.layout')

@section('main')
<div ng-controller="aspirantDashboardCtrl" ng-init='initPassagesPage(@json($subject), @json($topic))'>
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0 text-primary-light">Passages</h6>
         <p class="text-neutral-600 mt-4 mb-0">Subject: @{{ selectedSubject.name || '-' }} | Topic: @{{ selectedTopic.name || '-' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
         <a href="{{ url('/aspirant/subjects') }}/@{{ selectedSubject.id }}/topics" class="btn btn-sm btn-danger">
            <i class="ri-arrow-left-line"></i>
         </a>
         <button type="button" class="btn btn-sm btn-primary-600" ng-click="openPassageModal()" ng-disabled="!selectedTopic">
            <i class="ri-add-line"></i> Add Passage
         </button>
      </div>
   </div>

   <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table bordered-table table-heading-dark-mode mb-0">
               <thead>
                  <tr>
                     <th>SN.</th>
                     <th>Title</th>
                     <th>Passage</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="passage in passages track by passage.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{passage.title}}</td>
                     <td class="text-wrap" style="min-width: 320px;">@{{passage.passage}}</td>
                     <td>
                        <span ng-if="passage.status != 1" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                        <span ng-if="passage.status == 1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                     </td>
                     <td>
                        <div class="d-flex gap-2 flex-wrap">
                           <button type="button" class="btn btn-sm btn-info-100 text-info-600" ng-click="openPassageModal(passage)">Edit</button>
                           <a class="btn btn-sm btn-primary-600" href="{{ url('/aspirant/subjects') }}/@{{selectedSubject.id}}/topics/@{{selectedTopic.id}}/questions?passage_id=@{{passage.id}}">Questions</a>
                        </div>
                     </td>
                  </tr>
                  <tr ng-if="!passages.length">
                     <td colspan="5" class="text-center py-4 text-secondary-light">No passages found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <div class="modal fade" id="passageModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
         <div class="modal-content bg-base">
            <div class="modal-header">
               <div>
                  <h5 class="modal-title mb-1 text-primary-light">@{{ passageForm.id ? 'Edit Passage' : 'Add Passage' }}</h5>
                  <p class="text-neutral-600 mb-0">Create a passage for this topic and connect questions to it.</p>
               </div>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form ng-submit="savePassage()">
               <div class="modal-body">
                  <div class="row g-4">
                     <div class="col-12">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" ng-model="passageForm.title" required>
                     </div>
                     <div class="col-12">
                        <label class="form-label">Passage</label>
                        <textarea class="form-control" ng-model="passageForm.passage" rows="8" required></textarea>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-control" ng-model="passageForm.status">
                           <option value="0">Active</option>
                           <option value="1">Inactive</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="border border-danger-600 text-danger-600 px-50 py-11 radius-8" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary-600" ng-disabled="passageProcessing">
                     @{{ passageProcessing ? 'Saving...' : 'Save' }}
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
