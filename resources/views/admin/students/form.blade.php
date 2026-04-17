@extends('layout.layout')

@section('main')
<div ng-controller="addStudentCtrl" ng-init="init('{{ $student_token }}');">

    <div class="d-flex justify-content-between align-items-center mb-16">
        <h4 class="fw-bold text-lg">
            @{{formData.enc_id ? 'Edit Student' : 'Add Student'}}
        </h4>
        <a href="{{ url('admin/students') }}"
           class="border border-danger-600 bg-hover-danger-200 btn-sm text-danger-600 text-md px-50 py-11 radius-8">
            Back to List
        </a>
    </div>
    <form name="myForm" novalidate="novalidate" ng-submit="submit(myForm.$valid)">
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">Personal Info</h6>
            </div>
            <div class="card-body p-20">
                <div class="row g-3">
                   <!--  <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Admission No  <span class="text-danger-600">* </span> </label>
                       
                        <input type="text" class="form-control" ng-model="formData.admission_no">
                    </div> -->

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">SR. No <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.sr_no" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Roll. No <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.roll_no">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Class  <span class="text-danger-600">* </span> </label>
                        <select class="form-select" ng-model="formData.standard_id" convert-to-number required>
                            <option value="">Select</option>
                            <option value="@{{item.value}}" ng-repeat="item in standards">@{{item.label}}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8" >Section <span class="text-danger-600">* </span> </label>
                        <select class="form-select" ng-model="formData.section_id" convert-to-number required>
                            <option value="">Select</option>
                            <option value="1">A</option>
                            <option value="2">B</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">First Name <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.first_name" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Last Name  <span class="text-danger-600"> </span> </label>
                        <input type="text" class="form-control" ng-model="formData.last_name">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Date of Birth  <span class="text-danger-600">* </span> </label>
                        <input class="flatpickr flatpickr-input form-control"
                               type="text"
                               ng-model="formData.dob"
                               placeholder="Select Date.." required>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Gender  <span class="text-danger-600">* </span> </label>
                        <select class="form-select" ng-model="formData.gender" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mobile </label>
                        <input type="text" class="form-control" ng-model="formData.mobile">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Email  <span class="text-danger-600">* </span> </label>
                        <input type="email" class="form-control" ng-model="formData.email" ng-readonly="formData.id" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Aadhar No  <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.aadhar_no">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Relgion <span class="text-danger-600">* </span> </label>
                        <select class="form-select" ng-model="formData.religion_id" convert-to-number required>
                            <option value="">Select</option>
                            <option value="@{{item.value}}" ng-repeat="item in religions">@{{item.label}}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Cast </label>
                        <select class="form-select" ng-model="formData.cast_id" convert-to-number>
                            <option value="">Select</option>
                            <option value="@{{item.value}}" ng-repeat="item in casts">@{{item.label}}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Status  <span class="text-danger-600">* </span> </label>
                        <select class="form-select" ng-model="formData.approved" convert-to-number>
                            <option value="0">Active</option>
                            <option value="1">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-block mb-8">Photo</label>

                        <button type="button"
                            ng-if="!formData.student_photo || formData.student_photo == ''"
                            class="btn btn-primary-600 border border-primary-600 text-md px-28 py-8 radius-8"
                            ngf-select="uploadFile($file,'student_photo',formData)"
                            ng-hide="formData.uploading">
                            Select Image
                        </button>

                        <a ng-href="@{{formData.student_photo}}"
                           ng-show="formData.student_photo"
                           class="btn btn-primary-600 border border-primary-600 text-md px-28 py-8 radius-8"
                           target="_blank">
                           View Image
                        </a>

                        <button ng-show="formData.student_photo"
                            type="button"
                            class="btn btn-danger"
                            ng-click="removeFile(formData,'student_photo')">
                            X
                        </button>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-block mb-8">Aadhar Card</label>
                        <button type="button"
                            ng-if="!formData.aadhar_card || formData.aadhar_card == ''"
                            class="btn btn-primary-600 border border-primary-600 text-md px-28 py-8 radius-8"
                            ngf-select="uploadFile($file,'aadhar_card',formData)"
                            ng-hide="formData.uploading">
                            Select Image
                        </button>

                        <a ng-href="@{{formData.aadhar_card}}"
                           ng-show="formData.aadhar_card"
                           class="btn btn-primary-600 border border-primary-600 text-md px-28 py-8 radius-8"
                           target="_blank">
                           View Image
                        </a>

                        <button ng-show="formData.aadhar_card"
                            type="button"
                            class="btn btn-danger"
                            ng-click="removeFile(formData,'aadhar_card')">
                            X
                        </button>
                    </div>


                                    
                </div>
            </div>
        </div>
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">Parent & Guardian Info</h6>
            </div>
            <div class="card-body p-20">
                <div class="row g-3">
                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Name  <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.father_name" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Mobile <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.father_mobile" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Email</label>
                        <input type="email" class="form-control"
                               ng-model="formData.father_email"
                               ng-readonly="formData.id">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Father's Aadhar No</label>
                        <input type="text" class="form-control" ng-model="formData.father_aadhar_no">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Name <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.mother_name" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Mobile</label>
                        <input type="text" class="form-control" ng-model="formData.mother_mobile">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Email</label>
                        <input type="email" class="form-control" ng-model="formData.mother_email">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Mother's Aadhar No</label>
                        <input type="text" class="form-control" ng-model="formData.mother_aadhar_no">
                    </div>

                    
                </div>

                
                
            </div>
        </div>
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">Medical Info</h6>
            </div>
            <div class="card-body p-20">
                <div class="row g-3">
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Blood Group <span class="text-danger-600">* </span> </label>
                        <select class="form-select" ng-model="formData.blood_group_id" convert-to-number>
                            <option value="">Select</option>
                            <option value="@{{item.value}}" ng-repeat="item in blood_groups">@{{item.label}}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Weight <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.weight" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Height  <span class="text-danger-600">* </span> </label>
                        <input type="text" class="form-control" ng-model="formData.height" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">Previous School Details</h6>
            </div>
            <div class="card-body p-20">
                <div class="row g-3">
                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">School Name</label>
                        <input type="text" class="form-control" ng-model="formData.previous_school">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Address</label>
                        <input type="text" class="form-control" ng-model="formData.previous_school_address">
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-16">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">Address Details</h6>
            </div>
            <div class="card-body p-20">
                <div class="row g-3">
                    <div class="form-group col-md-12">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Permanent Address<span class="text-danger-600">*</span>
                        </label>

                        <textarea class="form-control" rows="1" ng-model="formData.permanent_address" required></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Residential Address<span class="text-danger-600">*</span>
                        </label>

                        <div class="form-check mb-2 d-inline-flex">
                            <input class="form-check-input" type="checkbox" ng-model="sameAs" ng-change="copyAddress()" id="sameAsCheck">
                            <label class="form-check-label" for="sameAsCheck">
                                Same as Permanent Address
                            </label>
                        </div>
                        <textarea class="form-control" rows="1" ng-model="formData.residential_address" required></textarea>
                    </div>

                    
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3 mt-16 mb-24">
            <a href="{{url('admin/students/add')}}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-50 py-11 radius-8">
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