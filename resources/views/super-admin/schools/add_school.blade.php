@extends('layout.layout')

@section('main')
<div ng-controller="superAdminUsersCtrl" ng-init="sch_id = {{$sch_id}}; addSchool();" class="mt-24">
    <div class="d-flex justify-content-between align-items-center mb-16">
        <h4 class="fw-bold text-lg">
            @{{formData.sch_id ? 'Edit School' : 'Add School'}}
        </h4>
        <a href="{{ url('super-admin/schools') }}"
           class="border border-danger-600 bg-hover-danger-200 btn-sm text-danger-600 text-md px-50 py-11 radius-8">
            Back to List
        </a>
    </div>

    <form name="myForm" class="school-form" novalidate="novalidate" ng-submit="submit()">
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">School Info</h6>
            </div>
            <div class="card-body p-20">
                <div class="row g-3">

                    <!-- School Name -->
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">School Name  <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.school_name">
                    </div>

                    <!-- Owner Name -->
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Owner Name  <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.name">
                    </div>

                    <!-- Start Date -->
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Start Date  <span class="text-danger-600">* </span> </label>
                        <input type="date" class="form-control" ng-model="formData.subscription_start_date">
                    </div>

                    <!-- End Date -->
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">End Date  <span class="text-danger-600">* </span> </label>
                        <input type="date" class="form-control" ng-model="formData.subscription_end_date" min="@{{formData.subscription_start_date}}">
                    </div>

                    <!-- GST -->
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">GST Number</label>
                        <input type="text" class="form-control" ng-model="formData.gst">
                    </div>

                    <!-- Email -->
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Email  <span class="text-danger-600">* </span> </label>
                        <input type="email" class="form-control" ng-model="formData.email">
                    </div>

                    <!-- Mobile -->
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mobile  <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.mobile">
                    </div>

                    <!-- Address -->
                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Address</label>
                        <input type="text" class="form-control" ng-model="formData.address">
                    </div>

                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="text-center mt-16 mb-24">
            <a href="{{url('super-admin/schools')}}"
               class="border border-danger-600 bg-hover-danger-200 btn-sm text-danger-600 text-md px-50 py-11 radius-8">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary-600 border border-primary-600 text-md px-28 py-8 radius-8" ng-disabled="processing || myForm.$invalid">
                @{{processing ? 'Saving...' : 'Save Changes'}}
            </button>
        </div>
    </form>
</div>
@endsection
