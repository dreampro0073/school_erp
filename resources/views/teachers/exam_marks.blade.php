@extends('layout.layout')

@section('main')
<div ng-controller="examMarksCtrl" ng-init="init();" class="mt-24">
    <div class="d-flex justify-content-between align-items-center mb-16">
        <h5 class="mb-0">Exam Marks</h5>
    </div>

    <div class="card mb-16">
        <div class="card-body">
            <form class="row g-3" ng-submit="saveMark()">
                <div class="col-md-3">
                    <label class="form-label">Exam Name</label>
                    <input type="text" class="form-control" ng-model="formData.exam_name" placeholder="Mid Term / Final" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Exam Date</label>
                    <input type="date" class="form-control" ng-model="formData.exam_date" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Student</label>
                    <select class="form-select" ng-model="formData.student_id" required>
                        <option value="">Select Student</option>
                        <option ng-repeat="student in students track by student.id" value="@{{student.id}}">
                            @{{student.name}} <span ng-if="student.admission_no">(@{{student.admission_no}})</span>
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subject</label>
                    <select class="form-select" ng-model="formData.subject_id">
                        <option value="">General</option>
                        <option ng-repeat="subject in subjects track by subject.id" value="@{{subject.id}}">@{{subject.name}}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Total</label>
                    <input type="number" min="1" step="0.01" class="form-control" ng-model="formData.total_marks" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Obt.</label>
                    <input type="number" min="0" step="0.01" class="form-control" ng-model="formData.obtained_marks" required>
                </div>
                <div class="col-md-10">
                    <label class="form-label">Remark</label>
                    <input type="text" class="form-control" ng-model="formData.remark" placeholder="Optional remark">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100" type="submit" ng-disabled="saving">
                        @{{ formData.id ? 'Update' : 'Add' }}
                    </button>
                    <button class="btn btn-outline-secondary" type="button" ng-click="resetForm()">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" ng-model="filters.exam_name" placeholder="Filter by exam name">
                </div>
                <div class="col-md-3">
                    <select class="form-select" ng-model="filters.student_id">
                        <option value="">All Students</option>
                        <option ng-repeat="student in students track by student.id" value="@{{student.id}}">@{{student.name}}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" ng-model="filters.subject_id">
                        <option value="">All Subjects</option>
                        <option ng-repeat="subject in subjects track by subject.id" value="@{{subject.id}}">@{{subject.name}}</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary w-100" ng-click="loadRows()">Apply</button>
                    <button type="button" class="btn btn-outline-secondary w-100" ng-click="resetFilters()">Clear</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Exam</th>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Total</th>
                            <th>Obtained</th>
                            <th>%</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="row in rows track by row.id">
                            <td>@{{$index + 1}}</td>
                            <td>@{{row.exam_name}}</td>
                            <td>@{{row.exam_date}}</td>
                            <td>@{{row.student_name}}</td>
                            <td>@{{row.subject_name}}</td>
                            <td>@{{row.total_marks}}</td>
                            <td>@{{row.obtained_marks}}</td>
                            <td>@{{row.percentage}}%</td>
                            <td>@{{row.remark || '-'}}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" ng-click="editRow(row)">Edit</button>
                            </td>
                        </tr>
                        <tr ng-if="!rows.length">
                            <td colspan="10" class="text-center py-4 text-secondary">No exam marks found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

