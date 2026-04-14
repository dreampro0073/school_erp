@extends('layout.layout')

@section('main')
<div ng-controller="aspirantDashboardCtrl" ng-init='initQuestionsPage(@json($subject), @json($topic))'>
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0">Questions</h6>
         <p class="text-neutral-600 mt-4 mb-0">Subject: @{{ selectedSubject.name || '-' }} | Topic: @{{ selectedTopic.name || '-' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
         <a href="{{ url('/aspirant/subjects') }}/@{{ selectedSubject.id }}/topics" class="btn btn-sm btn-danger">
            <i class="ri-arrow-left-line"></i>
         </a>
         <button type="button" class="btn btn-sm btn-primary-600" ng-click="openQuestionModal()" ng-disabled="!selectedTopic">
            <i class="ri-add-line"></i> Add Question
         </button>
      </div>
   </div>

   <div class="card">
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>SN.</th>
                     <th>Question</th>
                     <th>Marks</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="question in questions track by question.id">
                     <td>@{{$index + 1}}</td>
                     <td>@{{question.question}}</td>
                     <td>
                        @{{question.total_marks || 0}}
                     </td>
                     <td>
                        <button type="button" class="btn btn-sm btn-info-100 text-info-600" ng-click="openQuestionModal(question)">Edit</button>
                     </td>
                  </tr>
                  <tr ng-if="!questions.length">
                     <td colspan="4" class="text-center py-4">No questions found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <div class="modal fade" id="questionModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
               <div>
                  <h5 class="modal-title mb-2">@{{ questionForm.id ? 'Edit Question' : 'Add Question' }}</h5>
                  <p class="text-neutral-600 mb-0">Fill in the details below.</p>
               </div>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form ng-submit="saveQuestion()">
               <div class="modal-body">
                  <div class="row g-3">
                     <div class="col-12">
                        <label class="form-label">Question</label>
                        <textarea class="form-control" ng-model="questionForm.question" rows="4" required></textarea>
                     </div>
                     <div class="col-12">
                        <label class="form-label">Question (Hindi)</label>
                        <textarea class="form-control" ng-model="questionForm.question_hi" rows="3"></textarea>
                     </div>
                     <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" ng-model="questionForm.remarks" rows="2"></textarea>
                     </div>

                     <div class="col-md-6">
                        <label class="form-label">Option A</label>
                        <input type="text" class="form-control" ng-model="questionForm.opt_a">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Option B</label>
                        <input type="text" class="form-control" ng-model="questionForm.opt_b">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Option C</label>
                        <input type="text" class="form-control" ng-model="questionForm.opt_c">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Option D</label>
                        <input type="text" class="form-control" ng-model="questionForm.opt_d">
                     </div>

                     <div class="col-md-6">
                        <label class="form-label">Answer</label>
                        <input type="text" class="form-control" ng-model="questionForm.answer">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Negative Marks</label>
                        <input type="number" step="0.01" class="form-control" ng-model="questionForm.negative_marks">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Paragraph ID</label>
                        <input type="number" class="form-control" ng-model="questionForm.paragraph_id">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Image File</label>
                        <input type="text" class="form-control" ng-model="questionForm.image_file">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Total Marks</label>
                        <input type="number" class="form-control" ng-model="questionForm.total_marks">
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary-600" ng-disabled="questionProcessing">
                     @{{ questionProcessing ? 'Saving...' : 'Save' }}
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
