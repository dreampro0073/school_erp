@extends('layout.layout')

@section('main')
<div ng-controller="aspirantDashboardCtrl" ng-init="init();" class="container-fluid px-0">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0 text-primary-light">Aspirant Dashboard</h6>
            <p class="text-neutral-600 mt-4 mb-0">Welcome, @{{ aspirant.name || 'Aspirant' }}.</p>
        </div>
        <span class="px-12 py-5-px border border-neutral-300 radius-8 text-secondary-light text-sm d-inline-flex align-items-center">@{{ today }}</span>
    </div>

    <div class="row gy-3 mb-24">
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Name</p>
                    <h6 class="mb-0 text-primary-light">@{{ aspirant.name || '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Email</p>
                    <h6 class="mb-0 text-primary-light">@{{ aspirant.email || '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-1 radius-12 border-0 h-100 bg-base">
                <div class="card-body">
                    <p class="text-secondary-light mb-4">Mobile</p>
                    <h6 class="mb-0 text-primary-light">@{{ aspirant.mobile || '-' }}</h6>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
