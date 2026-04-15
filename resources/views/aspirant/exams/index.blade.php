@extends('layout.layout')

@section('main')
<div ng-controller="examCtrl" ng-init="init()" class="container-fluid px-0">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0 text-primary-light">Online Examination</h6>
            <p class="text-neutral-600 mt-4 mb-0">Pick at least three subjects, start a 100-question exam, and continue safely even after a reload.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap" ng-if="examState === 'running'">
            <span class="badge bg-base border text-primary-light px-3 py-2">Progress: @{{ currentQuestionIndex + 1 }}/@{{ questions.length || 100 }}</span>
            <span class="badge bg-base border text-primary-light px-3 py-2">Answered: @{{ getAnsweredCount() }}</span>
            <span class="badge bg-danger-100 text-danger-600 px-3 py-2">Time Left: @{{ formatTime(timeLeft) }}</span>
        </div>
    </div>

    <div class="card shadow-1 radius-12 border-0 bg-base" ng-if="loading">
        <div class="card-body py-5 text-center">Loading exam data...</div>
    </div>

    <div ng-if="!loading && examState === 'entry'">
        <div class="card shadow-1 radius-12 border-0 bg-base">
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
                        <div><span class="legend-dot answered"></span> Answered</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="card shadow-1 radius-12 border-0 bg-base" ng-if="getCurrentQuestion()">
                <div class="card-body p-24">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-20">
                        <div>
                            <p class="text-sm text-secondary-light mb-2">Question @{{ currentQuestionIndex + 1 }} of @{{ questions.length }}</p>
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
                                    <strong>@{{ option.key }}.</strong>
                                    <span>@{{ option.text }}</span>
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
                <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Total Score</p>
                        <h3 class="mb-0 text-primary-light">@{{ result.total_score }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Correct</p>
                        <h4 class="mb-0 text-success-600">@{{ result.correct }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Wrong</p>
                        <h4 class="mb-0 text-danger-600">@{{ result.wrong }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Attempted</p>
                        <h4 class="mb-0 text-primary-light">@{{ result.attempted }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                    <div class="card-body">
                        <p class="text-secondary-light mb-4">Unattempted</p>
                        <h4 class="mb-0 text-primary-light">@{{ result.unattempted }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-1 radius-12 border-0 mb-24 bg-base">
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

        <div class="card shadow-1 radius-12 border-0 bg-base" ng-if="showAnswerKey">
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
                                    <span class="badge"
                                        ng-class="{
                                            'bg-success-100 text-success-600': item.status === 'correct',
                                            'bg-danger-100 text-danger-600': item.status === 'incorrect',
                                            'bg-warning-100 text-warning-600': item.status === 'unattempted'
                                        }">
                                        @{{ item.status }}
                                    </span>
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
        border: 0;
        border-radius: 12px;
        min-height: 48px;
        font-weight: 700;
        transition: all 0.2s ease;
        background: #111111;
        color: #ffffff;
    }

    .palette-item.visited {
        background: #bf4d28;
    }

    .palette-item.answered {
        background: #1d8f5b;
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
        background: #1d8f5b;
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
        display: none;
    }

    .exam-option-card.selected {
        border-color: #0e66aa;
        background: #eef7ff;
        box-shadow: 0 10px 30px rgba(14, 102, 170, 0.1);
    }

    [data-theme=dark] .exam-subject-card {
        border-color: #4b5563;
        background: linear-gradient(135deg, #1f2937 0%, #243447 100%);
    }

    [data-theme=dark] .exam-subject-card input {
        accent-color: #60a5fa;
    }

    [data-theme=dark] .palette-legend {
        color: #d1d5db;
    }

    [data-theme=dark] .exam-option-card {
        background: #1f2937;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    [data-theme=dark] .exam-option-card.selected {
        border-color: #60a5fa;
        background: rgba(29, 128, 252, 0.14);
        box-shadow: 0 10px 30px rgba(14, 102, 170, 0.18);
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
