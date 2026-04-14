@extends('layout.layout')

@section('main')
<div ng-controller="aspirantDashboardCtrl" ng-init="init();" class="container-fluid px-0">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Aspirant Dashboard</h6>
            <p class="text-neutral-600 mt-4 mb-0">Welcome, @{{ aspirant.name || 'Aspirant' }}.</p>
        </div>
        <span class="badge text-bg-light px-3 py-2">@{{ today }}</span>
    </div>

    <div class="row gy-3 mb-24">
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Name</p>
                    <h6 class="mb-0">@{{ aspirant.name || '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Email</p>
                    <h6 class="mb-0">@{{ aspirant.email || '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Mobile</p>
                    <h6 class="mb-0">@{{ aspirant.mobile || '-' }}</h6>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
