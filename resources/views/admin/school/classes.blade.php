<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="text-lg fw-semibold mb-0">Class List</h6>
        </div>
        <button type="button"
                class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6"
                ng-click="openAddModal()">
            <span class="d-flex text-md">
                <i class="ri-add-large-line"></i>
            </span>
            Add Class
        </button>
    </div>

    <div class="card-body p-20">
        <div class="table-responsive">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th>SN.</th>
                        <th>Class</th>
                        <th>Session</th>
                        <th>Verifed</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in dataSet">
                        <td>@{{$index + 1}}</td>
                        <td>@{{item.standard_name}} <span ng-if="item.section_name">(@{{item.section_name}})</span></td>
                        <td>@{{item.period}}</td>
                        <td>
                            <span ng-if="item.is_verified == 0" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Pending</span>
                            <span ng-if="item.is_verified == -1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Deactivate</span>
                            <span ng-if="item.is_verified == 1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Verified</span>
                        </td>
                        <td>
                            <span ng-if="item.is_verified == 0 || item.is_verified == -1">
                                <button type="button" class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6" ng-click="openEditModal(item)"> Edit </button>

                                <button type="button" class="btn btn-sm btn-danger-100 text-info-600 me-8" ng-click="deleteClass(item)"> Delete </button>

                                <button ng-if="item.status == 0 && item.is_verified == 0" type="button" class="btn btn-sm btn-info-100 text-info-600 me-8" ng-click="changeClassStatus(item, 1)"> Verify </button>
                            </span>

                            <button ng-if="item.is_verified > -1" type="button" class="btn btn-sm btn-danger-100 text-info-600 me-8" ng-click="changeClassStatus(item, -1)"> Deactivate </button>

                            <button ng-if="item.is_verified < 0" type="button" class="btn btn-sm btn-info-100 text-info-600 me-8" ng-click="changeClassStatus(item, 0)"> Activate </button>
                            <a href=""></a>
                            <a href=""></a>
                        </td>
                    </tr>

                    <tr ng-if="!dataSet.length">
                        <td colspan="5" class="text-center py-4">No standards found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="my-sidebar bg-white position-fixed end-0 top-0 h-100vh overflow-y-auto z-99 max-w-700-px w-100 translate-x-full duration-300"
     ng-class="{'active-translate-0': isSidebarOpen}" style="z-index: 9999!;">

    <div class="px-20 py-12 border-bottom d-flex align-items-center justify-content-between gap-20">
        <h5 class="text-lg mb-0">@{{ isEditMode ? 'Edit Class' : 'Add New Class' }}</h5>
        <button type="button" class="close-my-sidebar text-danger-600 text-lg d-flex" ng-click="closeSidebar()">
            <i class="ri-close-large-line"></i>
        </button>
    </div>

    <form ng-submit="submitClass()" class="d-flex flex-column p-20">
        <div class="row g-3">

            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Class</label>
                <select class="form-select" ng-model="formData.standard_id" convert-to-number>
                    <option value="">Select Class</option>
                    <option value="@{{key}}" ng-repeat="(key, value) in standards">@{{value}}</option>
                </select>
            </div>

            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Section</label>
                <select class="form-select" ng-model="formData.section_id" convert-to-number>
                    <option value="">Select Section</option>
                    <option value="@{{key}}" ng-repeat="(key, value) in sections">@{{value}}</option>
                </select>
            </div>

            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Session</label>
                <select class="form-select" ng-model="formData.session_id" convert-to-number>
                    <option value="">Select Session</option>
                    <option value="@{{key}}" ng-repeat="(key, value) in sessions">@{{value}}</option>
                </select>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-center gap-3 mt-8">
                    <button type="button" ng-click="resetForm()" class="border border-danger-600 text-danger-600 px-50 py-11 radius-8"> Cancel </button>

                    <button type="submit" class="btn btn-primary-600 px-28 py-12 radius-8 w-100">
                        @{{ isEditMode ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<div class="overlay" ng-class="{'active': isSidebarOpen}" ng-click="closeSidebar()"></div>