@extends('layout.layout')

@section('main')
<div ng-controller="superAdminUsersCtrl" class="mt-24">
    <div class="d-flex justify-content-between align-items-center mb-16">
        <h4 class="fw-bold text-lg">
            @{{formData.sch_id ? 'Edit School' : 'Add School'}}
        </h4>
        <a href="{{ url('admin/teachers') }}"
           class="border border-danger-600 bg-hover-danger-200 btn-sm text-danger-600 text-md px-50 py-11 radius-8">
            Back to List
        </a>
    </div>

    <form name="myForm" class="teacher-form" novalidate="novalidate" ng-submit="submit()">
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0"> </h6>
            </div>
            <div class="card-body p-20">
                <div class="row g-3">
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">School Name <span class="text-danger-600">* </span></label>
                        <input type="text" class="form-control" name="name" ng-model="formData.name" required>
                        <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.name.$touched || myForm.$submitted) && myForm.name.$error.required">
                            School name is required.
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Name of owner <span class="text-danger-600">* </span></label>
                        <input type="text" class="form-control" name="client_name" ng-model="formData.client_name" required>
                        <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.client_name.$touched || myForm.$submitted) && myForm.first_name.$error.required">
                            Owner name is required.
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Email <span class="text-danger-600">* </span></label>
                        <input type="email" class="form-control" name="email" ng-model="formData.email" required>
                        <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.email.$touched || myForm.$submitted) && myForm.email.$error.required">
                            Email is required.
                        </div>
                        <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.email.$touched || myForm.$submitted) && myForm.email.$error.email">
                            Enter a valid email address.
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mobile <span class="text-danger-600">* </span></label>
                        <input type="text" class="form-control" name="mobile" ng-model="formData.mobile" ng-pattern="/^[0-9]{10}$/" pattern="[0-9]{10}" maxlength="10" inputmode="numeric" required>
                        <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.mobile.$touched || myForm.$submitted) && myForm.mobile.$error.required">
                            Mobile number is required.
                        </div>
                        <div class="text-danger-600 text-xs mt-1" ng-if="(myForm.mobile.$touched || myForm.$submitted) && myForm.mobile.$error.pattern">
                            Enter a valid 10-digit mobile number.
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Address  </label>
                        <input type="text" class="form-control" ng-model="formData.previous_school_address">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3 mt-16 mb-24">
            <a href="{{url('admin/teachers')}}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-50 py-11 radius-8">
                Cancel
            </a>
            <button type="submit"
             class="btn btn-primary-600 border border-primary-600 text-md px-28 py-12 radius-8"
                 ng-disabled="processing">
                @{{processing ? 'Saving...' : 'Save Changes'}}
            </button>
        </div>
   </form>
</div>
@endsection

