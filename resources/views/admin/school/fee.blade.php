<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="fw-semibold mb-4 h6 text-primary-light">Fee Manage</h1>
        </div>
        <button type="button" class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6" ng-click="addFeeRow()">
            <span class="d-flex text-md"> <i class="ri-add-large-line"></i> </span> Add Fee
        </button>
    </div>

    <div class="card-body p-20">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 data-table dataTable" style="width: 1260.47px;">
                <thead>
                   <tr role="row">
                      <th>
                         <span class="dt-column-title" role="button">
                            <div class="form-check style-check d-flex align-items-center">
                               <input class="form-check-input" type="checkbox">
                               <label class="form-check-label"> S.L</label>
                            </div>
                         </span>
                         <span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Fee Type</span><span class="dt-column-order"></span>
                      </th>                      
                      <th>
                         <span class="dt-column-title" role="button">Frequency</span><span class="dt-column-order"></span>
                      </th>                      
                      <th>
                         <span class="dt-column-title" role="button">Einancial Year</span><span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Amount</span><span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Action</span><span class="dt-column-order"></span>
                      </th>
                   </tr>
                </thead>
                <tbody>
                   <tr ng-repeat="item in dataList track by $index">
                      <td>
                         <div class="form-check style-check d-flex align-items-center">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">
                            @{{$index+1}}
                            </label>
                         </div>
                      </td>
                      <td>
                         @{{item.fee_type}}<br>
                         <small>@{{item.description}}</small>
                      </td>
                      <td>
                         @{{item.fee_frequency}}
                      </td>                      
                      <td>
                         @{{item.period}}
                      </td>
                      <td>@{{item.amount}}</td>
                      <td>
                         <div class="btn-group">
                            <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                               <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                              <li>
                                  <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="editFeeRow(item, $index)"><i class="ri-edit-2-line"></i>Edit</button>
                               </li>
                               <!-- <li>
                                  <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" ng-click="deleteFeeRow(item, $index)"><i class="ri-delete-bin-6-line"></i>Remove</button>
                               </li> -->

                            </ul>
                         </div>
                      </td>
                   </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="my-sidebar theme-bg-white position-fixed end-0 top-0 h-100vh overflow-y-auto z-99 max-w-700-px w-100 translate-x-full duration-300"
     ng-class="{'active active-translate-0': isSidebarOpen}" style="z-index: 9999!;">

    <div class="px-20 py-12 border-bottom d-flex align-items-center justify-content-between gap-20">
        <h5 class="text-lg mb-0">@{{ isEditMode ? 'Edit Fee' : 'Add New Fee' }}</h5>
        <button type="button" class="close-my-sidebar text-danger-600 text-lg d-flex" ng-click="closeSidebar()">
            <i class="ri-close-large-line"></i>
        </button>
    </div>

    <form name="myForm" novalidate="novalidate" ng-submit="updateFeeRow()" class="d-flex flex-column p-20">
        <div class="row g-3">
            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Fee Type <span class="text-danger-600">*</span></label>
                <select class="form-select" ng-model="formData.fee_type_id" convert-to-number required>
                    <option value="">Select</option>
                    <option value="@{{item.value}}" ng-repeat="item in fee_types">@{{item.label}}</option>
                </select>
            </div>

            <div class="col-sm-12 form-group" ng-if="formData.fee_type_id == 2 || formData.fee_type_id == 3">
                <label class="text-sm fw-semibold text-primary-light mb-8">Frequency <span class="text-danger-600">* </span></label>
                <select class="form-select" ng-model="formData.frequency_id" convert-to-number required>
                    <option value="">Select</option>
                    <option value="@{{item.value}}" ng-repeat="item in fee_frequencies">@{{item.label}}</option>
                </select>
            </div>            

            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Financial year</label>
                <select class="form-select" ng-model="formData.fin_year" required convert-to-number>
                    <option value="">Select</option>
                    <option value="@{{key}}" ng-repeat="(key, value) in years">@{{value}}</option>
                </select>
            </div>

            <div class="col-sm-12 form-group">
                <label class="text-sm fw-semibold text-primary-light mb-8">Amount <span class="text-danger-600">*</span></label>
                <input type="number" step="0.01" min="0" class="form-control" ng-model="formData.amount" required>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-center gap-3 mt-8">
                    <button type="button" ng-click="resetForm()" class="border border-danger-600 text-danger-600 px-50 py-11 radius-8"> Cancel </button>

                    <button type="submit" class="btn btn-primary-600 px-28 py-12 radius-8 w-100" ng-disabled="processing">
                        @{{ isEditMode ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>
        </div>


    </form>
</div>

<div class="overlay" ng-class="{'active': isSidebarOpen}" ng-click="closeSidebar()"></div>
