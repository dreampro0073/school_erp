<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="fw-semibold mb-4 h6 text-primary-light">Students List </h1>
        </div>
        <button type="button"
                class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6"
                ng-click="openAddModal()">
            <span class="d-flex text-md">
                <i class="ri-add-large-line"></i>
            </span>
            Add Students
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
                               <label class="form-check-label">
                               S.L
                               </label>
                            </div>
                         </span>
                         <span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Admission No</span><span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Name</span><span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Date of Birth</span><span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Gender</span><span class="dt-column-order"></span>
                      </th>
                      <th>
                         <span class="dt-column-title" role="button">Mobile Number</span><span class="dt-column-order"></span>
                      </th>
                      
                      <th>
                         <span class="dt-column-title" role="button">Status</span><span class="dt-column-order"></span>
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
                         <span class="text-primary-600">AD52365</span>
                      </td>
                      <td>
                         <div class="d-flex align-items-center">
                            <img src="assets/images/thumbs/avatar-img1.png" alt="Image" class="flex-shrink-0 me-12 radius-8">
                            <div class="">
                               <h6 class="text-md mb-0 fw-medium flex-grow-1">@{{item.first_name}}</h6>
                               <span class="">Roll No: <span class="fw-semibold">12</span> </span>
                            </div>
                         </div>
                      </td>
                      <td>Class 1 (A)</td>
                      <td>@{{item.dob}}</td>
                      <td>Male</td>
                      <td>@{{item.mobile}}</td>
                      
                      <td>
                         <span class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                      </td>
                      <td>
                         <div class="btn-group">
                            <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                               <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                               <li>
                                  <a href="{{url('admin/students/profile/')}}/@{{item.unique_id}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                  <i class="ri-user-3-line"></i>
                                  View
                                  </a>
                               </li>
                               <li>
                                  <a href="{{url('admin/students/add/')}}/@{{item.unique_id}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                  <i class="ri-edit-2-line"></i>
                                     Edit
                                  </a>
                               </li>
                               <li>
                                  <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                  <i class="ri-money-dollar-box-line"></i>
                                     Collect Fees
                                  </button>
                               </li>
                               <li>
                                  <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" data-bs-toggle="modal" data-bs-target="#exampleModalDelete"><i class="ri-delete-bin-6-line"></i>Remove</button>
                               </li>
                            </ul>
                         </div>
                      </td>
                   </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="my-sidebar bg-white position-fixed end-0 top-0 h-100vh overflow-y-auto z-99 max-w-700-px w-100 translate-x-full duration-300"
     ng-class="{'active active-translate-0': isSidebarOpen}" style="z-index: 9999!;">

    <div class="px-20 py-12 border-bottom d-flex align-items-center justify-content-between gap-20">
        <h5 class="text-lg mb-0">@{{ isEditMode ? 'Edit Class' : 'Add New Class' }}</h5>
        <button type="button" class="close-my-sidebar text-danger-600 text-lg d-flex" ng-click="closeSidebar()">
            <i class="ri-close-large-line"></i>
        </button>
    </div>

    <form ng-submit="submitClass()" class="d-flex flex-column p-20">
        <div class="row g-3">


        </div>
    </form>
</div>

<div class="overlay" ng-class="{'active': isSidebarOpen}" ng-click="closeSidebar()"></div>