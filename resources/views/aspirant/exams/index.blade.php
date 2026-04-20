@extends('layout.layout')

@section('main')
<div ng-controller="examCtrl" ng-init="init()" class="container-fluid px-0">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0 text-primary-light">Online Examination</h6>
            <p class="text-neutral-600 mt-4 mb-0">Pick at least three subjects, start a 100-question exam, and continue safely even after a reload.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap" ng-if="examState === 'running'">
            <span class="px-12 py-5-px border border-neutral-300 radius-8 text-secondary-light text-sm d-inline-flex align-items-center">Progress: @{{ currentQuestionIndex + 1 }}/@{{ questions.length || 100 }}</span>
            <span class="px-12 py-5-px border border-neutral-300 radius-8 text-secondary-light text-sm d-inline-flex align-items-center">Answered: @{{ getAnsweredCount() }}</span>
            <span class="bg-danger-100 text-danger-600 px-12 py-5-px radius-8 text-sm d-inline-flex align-items-center">Time Left: @{{ formatTime(timeLeft) }}</span>
        </div>
    </div>

    <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden" ng-if="loading">
        <div class="card-body py-5 text-center">Loading exam data...</div>
    </div>

    <div ng-if="!loading && examState === 'entry'">
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
            <div class="card-body p-24">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-20">
                    <div>
                        <h6 class="mb-4 text-primary-light">Choose Subjects</h6>
                        <p class="text-secondary-light mb-0">Minimum 3 subjects are required to generate a full exam paper.</p>
                    </div>
                    <button type="button" class="btn btn-primary-600" ng-click="startExam()" ng-disabled="processing">
                        @{{ processing ? 'Starting...' : 'Start Exam' }}
                    </button>
                </div>

                <div class="alert alert-danger py-2" ng-if="errorMessage">@{{ errorMessage }}</div>

                <div class="row g-3">
                    <div class="col-md-4" ng-repeat="subject in subjects track by subject.id">
                        <label class="exam-subject-card d-flex align-items-start gap-3 w-100">
                            <input type="checkbox"
                                ng-checked="selectedSubjects[subject.id]"
                                ng-click="toggleSubject(subject.id)"
                                ng-disabled="processing">
                            <span>
                                <span class="d-block fw-semibold text-primary-light">@{{ subject.name }}</span>
                                <span class="d-block text-secondary-light text-sm">Subject ID: @{{ subject.id }}</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="mt-20 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <p class="mb-0 text-secondary-light">Selected: <strong>@{{ getSelectedSubjectCount() }}</strong> subject(s)</p>
                    <button type="button" class="btn btn-outline-primary" ng-click="restoreDraft()" ng-if="hasDraftExam()">Resume Saved Exam</button>
                </div>
            </div>
        </div>
    </div>

    <div ng-if="!loading && examState === 'running'" class="row g-4">
        <div class="col-xl-3">
            <div class="card shadow-1 radius-12 border-0 exam-palette-card bg-base">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between mb-16">
                        <h6 class="mb-0 text-primary-light">Question Palette</h6>
                        <span class="text-sm text-secondary-light">@{{ getAnsweredCount() }}/@{{ questions.length }}</span>
                    </div>

                    <div class="palette-grid">
                        <button type="button"
                            ng-repeat="question in questions track by question.id"
                            class="palette-item"
                            ng-class="getPaletteClass(question, $index)"
                            ng-click="goToQuestion($index)">
                            @{{ $index + 1 }}
                        </button>
                    </div>

                    <div class="palette-legend mt-20">
                        <div><span class="legend-dot not-answered"></span> Not Answered</div>
                        <div><span class="legend-dot visited"></span> Visited</div>
                        <div><span class="legend-dot answered"></span> Attempted</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden" ng-if="getCurrentQuestion()">
                <div class="card-body p-24">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-20">
                        <div>
                            <p class="text-sm text-secondary-light mb-2">Question @{{ currentQuestionIndex + 1 }} of @{{ questions.length }}</p>
                            <div class="exam-passage-box mb-16" ng-if="getCurrentQuestion().passage">
                                <p class="exam-passage-label mb-6">Passage</p>
                                <h6 class="mb-8 text-primary-light" ng-if="getCurrentQuestion().passage.title">@{{ getCurrentQuestion().passage.title }}</h6>
                                <p class="mb-0 text-secondary-light exam-passage-text">@{{ getCurrentQuestion().passage.description }}</p>
                            </div>
                            <h5 class="mb-0 text-primary-light">@{{ getCurrentQuestion().question }}</h5>
                        </div>
                        <button type="button" class="btn btn-danger" ng-click="submitExam(false)" ng-disabled="processing">Submit Exam</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6" ng-repeat="option in getQuestionOptions(getCurrentQuestion()) track by option.key">
                            <label class="exam-option-card w-100" ng-class="{'selected': answerMap[getCurrentQuestion().id] === option.key}">
                                <input type="radio"
                                    name="question_@{{ getCurrentQuestion().id }}"
                                    ng-model="answerMap[getCurrentQuestion().id]"
                                    ng-value="option.key"
                                    ng-change="selectAnswer(getCurrentQuestion(), option.key)"
                                    ng-disabled="processing">
                                <span class="d-flex align-items-start gap-3">
                                    <span class="exam-option-indicator"></span>
                                    <span>
                                        <strong>@{{ option.key }}.</strong>
                                        <span>@{{ option.text }}</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-24">
                        <button type="button" class="btn btn-outline-primary" ng-click="previousQuestion()" ng-disabled="currentQuestionIndex === 0">Previous</button>
                        <div class="text-secondary-light text-sm">Autosave is on. Answers are stored locally and on the server.</div>
                        <button type="button" class="btn btn-primary-600" ng-click="nextQuestion()" ng-disabled="currentQuestionIndex === questions.length - 1">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div ng-if="!loading && examState === 'result' && result">
        <div class="row g-3 mb-24">
            <div class="col-md-4">
                <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Total Score</p>
                        <h3 class="mb-0 text-primary-light result-stat-value">@{{ result.total_score }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Correct</p>
                        <h4 class="mb-0 text-success-600 result-stat-value">@{{ result.correct }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Wrong</p>
                        <h4 class="mb-0 text-danger-600 result-stat-value">@{{ result.wrong }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Attempted</p>
                        <h4 class="mb-0 text-primary-light result-stat-value">@{{ result.attempted }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Unattempted</p>
                        <h4 class="mb-0 text-primary-light result-stat-value">@{{ result.unattempted }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mb-24">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h6 class="mb-2 text-primary-light">Exam Submitted</h6>
                    <p class="text-secondary-light mb-0">Your answers are locked and the result has been calculated with negative marking.</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" ng-click="loadAnswerKey()" ng-disabled="answerKeyLoading">
                        @{{ answerKeyLoading ? 'Loading...' : 'View Answer Key' }}
                    </button>
                    <button type="button" class="btn btn-primary-600" ng-click="resetToEntry()">Start New Exam</button>
                </div>
            </div>
        </div>

        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden" ng-if="showAnswerKey">
            <div class="card-body p-0">
                <div class="p-20 border-bottom">
                    <h6 class="mb-0 text-primary-light">Answer Key</h6>
                </div>
                <div class="table-responsive">
                    <table class="table bordered-table table-heading-dark-mode mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Correct Answer</th>
                                <th>User Answer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in answerKey track by item.question_id">
                                <td>@{{ item.question_no }}</td>
                                <td>@{{ item.question }}</td>
                                <td>@{{ item.correct_answer || '-' }}</td>
                                <td>@{{ item.user_answer || '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge"
                                            ng-class="{
                                                'bg-success-100 text-success-600': item.status === 'correct',
                                                'bg-danger-100 text-danger-600': item.status === 'incorrect',
                                                'bg-warning-100 text-warning-600': item.status === 'unattempted'
                                            }">
                                            @{{ item.status }}
                                        </span>
                                        <button type="button"
                                            class="answerkey-info-btn"
                                            ng-click="showAnswerKeyInfo(item)"
                                            title="View reference and remarks"
                                            aria-label="View reference and remarks">
                                            <i class="ri-information-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr ng-if="!answerKey.length">
                                <td colspan="5" class="text-center py-4 text-secondary-light">No answer key available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="answerKeyInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-base">
                <div class="modal-header">
                    <h5 class="modal-title text-primary-light">Question Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-12 text-primary-light"><strong>Reference:</strong> @{{ answerKeyInfo.reference || '-' }}</p>
                    <p class="mb-0 text-primary-light"><strong>Remarks:</strong> @{{ answerKeyInfo.remarks || '-' }}</p>
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
    .exam-subject-card {
        border: 1px solid #d9e2f2;
        border-radius: 16px;
        padding: 18px;
        background: linear-gradient(135deg, #fdfaf3 0%, #f7fbff 100%);
        cursor: pointer;
        min-height: 100%;
    }

    .exam-subject-card input {
        margin-top: 4px;
        transform: scale(1.15);
    }

    .exam-palette-card {
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .palette-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 10px;
    }

    .palette-item {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 12px;
        width: 48px;
        height: 48px;
        min-height: 48px;
        padding: 0;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        transition: all 0.2s ease;
        background: #111111;
        color: #ffffff;
    }

    .palette-item.visited {
        background: #bf4d28;
    }

    .palette-item.answered {
        background: linear-gradient(135deg, #159957 0%, #0b7a43 100%);
        box-shadow: 0 10px 20px rgba(21, 153, 87, 0.25);
    }

    .palette-item.current {
        box-shadow: 0 0 0 3px rgba(14, 102, 170, 0.24);
        transform: translateY(-1px);
    }

    .palette-legend {
        display: grid;
        gap: 10px;
        font-size: 13px;
        color: #667085;
    }

    .legend-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
        vertical-align: middle;
    }

    .legend-dot.not-answered {
        background: #111111;
    }

    .legend-dot.visited {
        background: #bf4d28;
    }

    .legend-dot.answered {
        background: linear-gradient(135deg, #159957 0%, #0b7a43 100%);
    }

    .exam-option-card {
        display: block;
        border: 1px solid #d6dfec;
        border-radius: 16px;
        padding: 18px;
        cursor: pointer;
        background: #ffffff;
        min-height: 100%;
    }

    .exam-option-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .exam-option-indicator {
        width: 18px;
        height: 18px;
        min-width: 18px;
        border: 2px solid #98a2b3;
        border-radius: 50%;
        margin-top: 2px;
        position: relative;
        transition: all 0.2s ease;
    }

    .exam-option-card.selected {
        border-color: #0e66aa;
        background: #eef7ff;
        box-shadow: 0 10px 30px rgba(14, 102, 170, 0.1);
    }

    .exam-option-card.selected .exam-option-indicator {
        border-color: #0e66aa;
    }

    .exam-option-card.selected .exam-option-indicator::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #0e66aa;
        position: absolute;
        top: 3px;
        left: 3px;
    }

    .result-stat-value {
        padding-left: 5px;
    }

    .exam-passage-box {
        border: 1px solid #dbe7f3;
        border-radius: 16px;
        padding: 16px 18px;
        background: linear-gradient(135deg, #f9fbff 0%, #f6fdf8 100%);
    }

    .exam-passage-label {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: #e8f1fb;
        color: #0e66aa;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .exam-passage-text {
        white-space: pre-line;
        line-height: 1.7;
    }

    .answerkey-info-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #d6dfec;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f8fbff;
        color: #0e66aa;
        transition: all 0.2s ease;
    }

    .answerkey-info-btn:hover {
        background: #eef7ff;
        border-color: #0e66aa;
    }

    @media (max-width: 1199px) {
        .exam-palette-card {
            position: relative;
            height: auto;
            max-height: none;
        }
    }
</style>
@endsection
