@extends('layout.layout')

@section('main')
<div ng-controller="aspirantDashboardCtrl" ng-init='initTopicsPage(@json($subject))'>
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0 text-primary-light">Topics</h6>
         <p class="text-neutral-600 mt-4 mb-0">Subject: @{{ selectedSubject.name || '-' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
         <a href="{{ url('/aspirant/subjects/index') }}" class="btn btn-sm btn-danger">
            <i class="ri-arrow-left-line"></i>
         </a>
         <button type="button" class="btn btn-sm btn-primary-600" ng-click="openTopicSidebar()" ng-disabled="!selectedSubject">
            <i class="ri-add-line"></i> Add Topic
         </button>
      </div>
   </div>

   <div class="card bg-base">
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table bordered-table table-heading-dark-mode mb-0">
               <thead>
                  <tr>
                     <th>SN.</th>
                     <th>Topic</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="topic in topics track by topic.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{topic.name}}</td>
                     <td>
                        <span ng-if="topic.status != 1" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                        <span ng-if="topic.status == 1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                     </td>
                     <td>
                        <div class="d-flex gap-2 flex-wrap">
                           <button type="button" class="btn btn-sm btn-info-100 text-info-600" ng-click="openTopicSidebar(topic)">Edit</button>
                           <a class="btn btn-sm btn-warning-100 text-warning-600" href="{{ url('/aspirant/subjects') }}/@{{selectedSubject.id}}/topics/@{{topic.id}}/passages">Passages</a>
                           <a class="btn btn-sm btn-primary-600" href="{{ url('/aspirant/subjects') }}/@{{selectedSubject.id}}/topics/@{{topic.id}}/questions">Questions</a>
                        </div>
                     </td>
                  </tr>
                  <tr ng-if="!topics.length">
                     <td colspan="5" class="text-center py-4 text-secondary-light">No topics found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <div class="my-sidebar theme-bg-white position-fixed end-0 top-0 h-100vh overflow-y-auto z-99 max-w-700-px w-100 translate-x-full duration-300"
     ng-class="{'active active-translate-0': isTopicSidebarOpen}" style="z-index: 9999!;">
      <div class="d-flex align-items-center justify-content-between p-16 border-bottom">
         <h5 class="mb-0 text-primary-light">@{{ isTopicEditMode ? 'Edit Topic' : 'Add Topic' }}</h5>
         <button type="button" class="close-my-sidebar text-danger-600 text-lg d-flex" ng-click="closeTopicSidebar()">
            <i class="ri-close-line"></i>
         </button>
      </div>
      <form class="p-16" ng-submit="saveTopic()">
         <div class="mb-16">
            <label class="form-label">Topic Name</label>
            <input type="text" class="form-control" ng-model="topicForm.name" required>
         </div>
         <div class="mb-16">
            <label class="form-label">Status</label>
            <select class="form-control" ng-model="topicForm.status">
               <option value="0">Active</option>
               <option value="1">Inactive</option>
            </select>
         </div>
         <div class="d-flex justify-content-end gap-8">
            <button type="button" class="border border-danger-600 text-danger-600 px-50 py-11 radius-8" ng-click="closeTopicSidebar()">Cancel</button>
            <button type="submit" class="btn btn-primary-600" ng-disabled="topicProcessing">
               @{{ topicProcessing ? 'Saving...' : 'Save' }}
            </button>
         </div>
      </form>
   </div>
   <div class="overlay" ng-class="{'active': isTopicSidebarOpen}" ng-click="closeTopicSidebar()"></div>
</div>
@endsection
