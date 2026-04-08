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
                        <th>Verified</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in dataSet">
                        <td>@{{$index + 1}}</td>
                        <td>@{{item.name}}</td>
                        <td>
                            <span ng-if="item.is_verified == 1" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Verified</span>
                            <span ng-if="item.is_verified == -1 || item.is_verified == -2" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Deactivate</span>
                            <span ng-if="item.is_verified == 0 || item.is_verified === null || item.is_verified === undefined" class="bg-primary-100 text-primary-600 px-24 py-4 radius-4 fw-medium text-sm">Pending</span>
                        </td>
                        <td >

                            <div class="btn-group">
                                <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                   <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                    <span ng-if="item.is_verified == 0 || item.is_verified == -1">

                                        <li>
                                            <button type="button" ng-click="openEditModal(item)"class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6"> <i class="ri-edit-2-line"></i>Edit</button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="deleteClass(item)"><i class="ri-delete-bin-6-line"></i>Delete</button>
                                        </li>
                                        <li>
                                            <button ng-if="item.status == 0 && item.is_verified == 0" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="changeClassStatus(item, 1)"><i class="ri-error-warning-line"></i>Verify</button>
                                        </li>
                                    </span>

                                    <li>
                                        <button ng-if="item.is_verified > -1" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="changeClassStatus(item, -1)"><i class="ri-error-warning-line"></i>Deactivate</button>
                                    </li>                                        
                                    <li>
                                        <button ng-if="item.is_verified < 0" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="changeClassStatus(item, 0)"><i class="ri-error-warning-line"></i>Activate</button>
                                    </li>
                                    <li>
                                        <a ng-if="item.is_verified == 1" href="{{ url('/admin/school/class-manage') }}/@{{item.id}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6"><i class="ri-user-3-line"></i>Manage</a>
                                    </li>
                                </ul>
                            </div>
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

<div class="my-sidebar theme-bg-white position-fixed end-0 top-0 h-100vh overflow-y-auto z-99 max-w-700-px w-100 translate-x-full duration-300"
     ng-class="{'active active-translate-0': isSidebarOpen}" style="z-index: 9999!;">

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
                <input type="text" class="form-control" ng-model="formData.name" required>
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
