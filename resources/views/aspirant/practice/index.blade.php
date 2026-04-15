@extends('layout.layout')

@section('main')
<div ng-controller="practiceCtrl" ng-init="init()" class="container-fluid px-0">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0 text-primary-light">Practice</h6>
         <p class="text-neutral-600 mt-4 mb-0">Select subject and topics to start.</p>
      </div>
   </div>

   <div class="card mb-24 bg-base">
      <div class="card-body">
         <div class="row g-3">
            <div class="col-md-4">
               <label class="form-label">Subject</label>
               <select class="form-control" ng-model="selectedSubjectId" ng-change="onSubjectChange()">
                  <option value="">Select Subject</option>
                  <option ng-repeat="sub in subjects track by sub.id" value="@{{sub.id}}">@{{sub.name}}</option>
               </select>
            </div>
            <div class="col-md-8">
               <label class="form-label">Topics</label>
               <div class="d-flex flex-wrap gap-8">
                  <label class="btn btn-outline-primary text-sm" ng-repeat="topic in topics track by topic.id" ng-class="{ 'active': selectedTopicIds[topic.id] }">
                     <input type="checkbox" class="d-none" ng-checked="selectedTopicIds[topic.id]" ng-click="toggleTopic(topic.id)">
                     @{{ topic.name }}
                  </label>
                  <span class="text-neutral-500" ng-if="!topics.length">Select subject to load topics.</span>
               </div>
            </div>
         </div>
         <div class="mt-16">
            <button type="button" class="btn btn-primary-600" ng-click="startPractice()" ng-disabled="loading">Start Practice</button>
         </div>
      </div>
   </div>

   <div class="card bg-base" ng-if="currentQuestion">
      <div class="card-body">
         <div class="d-flex justify-content-between align-items-start gap-12 mb-16">
            <div>
               <h6 class="mb-8 text-primary-light">Question @{{ currentIndex + 1 }}</h6>
               <p class="mb-0 text-primary-light">@{{ currentQuestion.question }}</p>
            </div>
            <div class="bg-base border text-primary-light px-3 py-2 radius-8">Time: @{{ timeLeft }}s</div>
            <button type="button" class="btn btn-sm btn-outline-secondary" ng-click="showReference()">Reference</button>
         </div>

         <div class="row g-3 mb-16">
            <div class="col-md-6" ng-repeat="opt in getOptionList()">
               <button type="button" class="btn w-100 text-start border radius-8 px-12 py-12 aspirant-practice-option" ng-class="optionClass(opt.key)" ng-click="selectOption(opt.key)" ng-disabled="showAnswer">
                  <strong class="me-8">@{{ opt.key }}.</strong> @{{ opt.text }}
               </button>
            </div>
         </div>

         <div class="d-flex align-items-center gap-8 mb-16" ng-if="!showAnswer">
            <button type="button" class="btn btn-success-600" ng-click="submitAnswer()" ng-disabled="showAnswer">Submit</button>
         </div>

         <div class="alert alert-light border aspirant-practice-answer" ng-if="showAnswer">
            <div class="mb-8">
               <span class="fw-semibold">Your Answer:</span>
               <span ng-class="currentQuestion._is_correct ? 'text-success-600' : 'text-danger-600'">
                  @{{ currentQuestion._user_answer || '-' }}
               </span>
            </div>
            <div>
               <span class="fw-semibold">Correct Answer:</span>
               <span class="text-success-600">@{{ currentQuestion.answer || '-' }}</span>
            </div>
         </div>

         <div class="d-flex justify-content-between mt-16">
            <button type="button" class="btn btn-outline-primary" ng-click="prevQuestion()" ng-disabled="currentIndex <= 0">Previous</button>
            <button type="button" class="btn btn-primary-600" ng-click="nextQuestion()">Next</button>
         </div>
      </div>
   </div>

   <div class="modal fade" id="referenceModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content bg-base">
            <div class="modal-header">
               <h5 class="modal-title text-primary-light">Reference</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <p class="mb-12 text-primary-light"><strong>Reference:</strong> @{{ referenceQuestion.reference || '-' }}</p>
               <p class="mb-0 text-primary-light"><strong>Remarks:</strong> @{{ referenceQuestion.remarks || '-' }}</p>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('footer_scripts')
<style>
   [data-theme=dark] .aspirant-practice-option {
      background-color: #1f2937;
      border-color: #4b5563;
      color: #f3f4f6 !important;
   }

   [data-theme=dark] .aspirant-practice-answer {
      background-color: #1b2431;
      border-color: #4b5563;
      color: #f3f4f6;
   }
</style>
@endsection
