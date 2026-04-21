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
               <div class="practice-passage-box mb-12" ng-if="currentQuestion.passage">
                  <p class="practice-passage-label mb-6">Passage</p>
                  <h6 class="mb-8 text-primary-light" ng-if="currentQuestion.passage.title">@{{ currentQuestion.passage.title }}</h6>
                  <p class="mb-0 text-secondary-light practice-passage-text">@{{ currentQuestion.passage.passage }}</p>
               </div>
               <p class="mb-0 text-primary-light">@{{ currentQuestion.question }}</p>
            </div>
            <div class="px-12 py-5-px border border-neutral-300 radius-8 text-secondary-light text-sm">Time: @{{ timeLeft }}s</div>
            <button type="button" class="btn btn-sm btn-outline-secondary" ng-click="showReference()">Reference</button>
         </div>

         <div class="row g-3 mb-16">
            <div class="col-md-6" ng-repeat="opt in currentOptions track by opt.key">
               <label class="practice-option-card w-100" ng-class="optionClass(opt.key)" ng-click="selectOption(opt.key)">
                  <span class="d-flex align-items-start gap-3">
                     <span class="practice-option-key">@{{ opt.key }}</span>
                     <span class="practice-option-text">
                        @{{ opt.text }}
                     </span>
                  </span>
               </label>
            </div>
         </div>

         <div class="d-flex align-items-center gap-8 mb-16" ng-if="!showAnswer">
            <button type="button" class="btn btn-success-600" ng-click="submitAnswer()" ng-disabled="showAnswer">Submit</button>
         </div>

         <div class="bg-base border border-neutral-200 radius-8 p-16" ng-if="showAnswer">
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
               <button type="button" class="border border-danger-600 text-danger-600 px-50 py-11 radius-8" data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('footer_scripts')
<style>
   .practice-passage-box {
      background: linear-gradient(180deg, #f6faff 0%, #eef7ff 100%);
      border: 1px solid #d6e8f8;
      border-radius: 16px;
      padding: 16px 18px;
   }

   .practice-passage-label {
      text-transform: uppercase;
      letter-spacing: 0.08em;
      font-size: 12px;
      font-weight: 700;
      color: #0e66aa;
   }

   .practice-passage-text {
      white-space: pre-line;
      line-height: 1.7;
   }

   .practice-option-card {
      display: block;
      border: 1px solid #d6dfec;
      border-radius: 16px;
      padding: 18px;
      cursor: pointer;
      background: #ffffff;
      min-height: 100%;
      transition: all 0.2s ease;
   }

   .practice-option-card:hover {
      border-color: #0e66aa;
      background: #f8fbff;
   }

   .practice-option-key {
      width: 32px;
      height: 32px;
      min-width: 32px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 14px;
      color: #0e66aa;
      background: #eef4fb;
      border: 1px solid #d6dfec;
      transition: all 0.2s ease;
   }

   .practice-option-text {
      color: #1b2430;
      line-height: 1.6;
   }

   .practice-option-card.selected {
      border-color: #0e66aa;
      background: #eef7ff;
      box-shadow: 0 0 0 3px rgba(14, 102, 170, 0.12);
   }

   .practice-option-card.selected .practice-option-key {
      background: #0e66aa;
      border-color: #0e66aa;
      color: #ffffff;
   }

   .practice-option-card.correct {
      border-color: #1d8f5b;
      background: #edfdf3;
      color: #1d8f5b;
      box-shadow: 0 0 0 3px rgba(29, 143, 91, 0.12);
   }

   .practice-option-card.correct .practice-option-key {
      background: #1d8f5b;
      border-color: #1d8f5b;
      color: #ffffff;
   }

   .practice-option-card.incorrect {
      border-color: #d92d20;
      background: #fff1f3;
      color: #d92d20;
      box-shadow: 0 0 0 3px rgba(217, 45, 32, 0.12);
   }

   .practice-option-card.incorrect .practice-option-key {
      background: #d92d20;
      border-color: #d92d20;
      color: #ffffff;
   }
</style>
@endsection
