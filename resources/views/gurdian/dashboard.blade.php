@extends('admin.layout')

@section('main')
<div ng-controller="guardianDashboardCtrl" ng-init="init();" class="container-fluid px-0">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Guardian Dashboard</h6>
            <p class="text-neutral-600 mt-4 mb-0">Welcome, @{{ guardian.name || 'Guardian' }}.</p>
        </div>
        <span class="badge text-bg-light px-3 py-2">@{{ today }}</span>
    </div>

    <div class="row gy-3 mb-24">
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Name</p>
                    <h6 class="mb-0">@{{ guardian.name || '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Email</p>
                    <h6 class="mb-0">@{{ guardian.email || '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Mobile</p>
                    <h6 class="mb-0">@{{ guardian.mobile || '-' }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-1 radius-12 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-16">
                <h6 class="mb-0">Children</h6>
                <span class="badge bg-primary-600">@{{ children.length || 0 }}</span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Admission No.</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="child in children track by child.id">
                            <td>@{{ child.id }}</td>
                            <td>@{{ child.name }}</td>
                            <td>@{{ child.admission_no }}</td>
                            <td>
                                <span class="badge text-bg-success" ng-if="child.active == 1">Active</span>
                                <span class="badge text-bg-secondary" ng-if="child.active != 1">Inactive</span>
                            </td>
                        </tr>
                        <tr ng-if="!children.length">
                            <td colspan="4" class="text-center text-secondary py-4">No linked students found for this guardian account.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
