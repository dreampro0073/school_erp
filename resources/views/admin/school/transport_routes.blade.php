<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="text-lg fw-semibold mb-0">Transport</h6>
        </div>
        <button type="button"
            class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6"
            ng-click="openAddTransportModal()">
	        <span class="d-flex text-md">
	            <i class="ri-add-large-line"></i>
	        </span>
        	Add Transport
        </button>
    </div>

    <div class="card-body p-20">
        <div class="table-responsive">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th>SN.</th>
                        <th>Route</th>
                        <th>Frequency</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in transport_routes">
                        <td>@{{$index + 1}}</td>
                        <td>@{{item.route_name}}

                        </td>
                        <td>
                            <span class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">@{{item.fee_frequency.name}}</span>
                        </td>
                        <td>@{{item.description}}</td>
                        <td>@{{item.amount}}</td>
                        <td>
                        	<span ng-if="item.status == 0" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                            <span ng-if="item.status == 1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                            
                        </td>
                        
                        <td >

                            <div class="btn-group">
                                <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                   <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                	<li>
                                        <button type="button" ng-click="openEditTransportModal(item)"class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6"> <i class="ri-edit-2-line"></i>Edit</button>
                                    </li>
                                    <li>
                                        <button ng-if="item.status == 0" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="changeTransportStatus(item, 1)"><i class="ri-error-warning-line"></i>Inactive</button>
                                    </li>                                        
                                    <li>
                                        <button nng-if="item.status == 1" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="changeTransportStatus(item, 0)"><i class="ri-error-warning-line"></i>Activate</button>
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
        <h5 class="text-lg mb-0">@{{ isEditMode ? 'Edit' : 'Add New' }}</h5>
        <button type="button" class="close-my-sidebar text-danger-600 text-lg d-flex" ng-click="closeTransportSidebar()">
            <i class="ri-close-large-line"></i>
        </button>
    </div>

    <form ng-submit="submitTransport()" class="d-flex flex-column p-20">
        <div class="row g-3">

            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Route Name</label>
                <input type="text" class="form-control" ng-model="formData.route_name" required>
            </div>

            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Description</label>
                <input type="text" class="form-control" ng-model="formData.description" required>
            </div>
            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Frequency <span class="text-danger-600">* </span></label>
                <select class="form-select" ng-model="formData.frequency_id" convert-to-number required>
                    <option value="">Select</option>
                    <option value="@{{item.value}}" ng-repeat="item in fee_frequencies">@{{item.label}}</option>
                </select>
            </div>            

           <!--  <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Financial year</label>
                <select class="form-select" ng-model="formData.fin_year" required convert-to-number>
                    <option value="">Select</option>
                    <option value="@{{item.value}}" ng-repeat="item in years">@{{item.label}}</option>
                </select>
            </div> -->
            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Amount</label>
                <input type="text" class="form-control" ng-model="formData.amount" required>
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
