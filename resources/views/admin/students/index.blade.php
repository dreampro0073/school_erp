@extends('admin.layout')

@section('main')
   <div ng-controller="studentCtrl" ng-init="init();">
      <div class="mt-24">
         <div class="card h-100">
            <div class="card-body p-0 dataTable-wrapper">
               <div class="p-0">
                  <div id="dataTable_wrapper" class="dt-container dt-empty-footer">
                     <div class="dt-layout-row">
                        <div class="dt-layout-cell dt-start ">
                           <div class="dt-length">
                              <select name="dataTable_length" aria-controls="dataTable" class="dt-input" id="dt-length-0">
                                 <option value="10">10</option>
                                 <option value="25">25</option>
                                 <option value="50">50</option>
                                 <option value="100">100</option>
                              </select>
                              <label for="dt-length-0"> entries per page</label>
                           </div>
                        </div>
                        <div class="dt-layout-cell dt-end ">
                           <div class="dt-search"><label for="dt-search-0">Search:</label><input type="search" class="dt-input" id="dt-search-0" placeholder="" aria-controls="dataTable"></div>
                        </div>
                     </div>
                     <div class="dt-layout-row dt-layout-table">
                        <div class="dt-layout-cell ">
                           <table class="table bordered-table mb-0 data-table dataTable" id="dataTable" data-page-length="10" aria-describedby="dataTable_info" style="width: 1260.47px;">
                              <colgroup>
                                 <col data-dt-column="0" style="width: 86.5625px;">
                                 <col data-dt-column="1" style="width: 139.203px;">
                                 <col data-dt-column="2" style="width: 236.25px;">
                                 <col data-dt-column="3" style="width: 111.656px;">
                                 <col data-dt-column="4" style="width: 128.906px;">
                                 <col data-dt-column="5" style="width: 89.0312px;">
                                 <col data-dt-column="6" style="width: 150.5px;">
                                 <col data-dt-column="7" style="width: 103.703px;">
                                 <col data-dt-column="8" style="width: 132.562px;">
                                 <col data-dt-column="9" style="width: 82.0938px;">
                              </colgroup>
                              <thead>
                                 <tr role="row">
                                    <th scope="col" data-dt-column="0" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc dt-ordering-asc" aria-sort="ascending" aria-label="
                                       S.L
                                       : Activate to invert sorting" tabindex="0">
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
                                    <th scope="col" data-dt-column="1" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Admission No: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Admission No</span><span class="dt-column-order"></span></th>
                                    <th scope="col" data-dt-column="2" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Name: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Name</span><span class="dt-column-order"></span></th>
                                    <th scope="col" data-dt-column="3" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Class: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Class</span><span class="dt-column-order"></span></th>
                                    <th scope="col" data-dt-column="4" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Date of Birth: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Date of Birth</span><span class="dt-column-order"></span></th>
                                    <th scope="col" data-dt-column="5" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Gender: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Gender</span><span class="dt-column-order"></span></th>
                                    <th scope="col" data-dt-column="6" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Mobile Number: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Mobile Number</span><span class="dt-column-order"></span></th>
                                    
                                    <th scope="col" data-dt-column="8" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Status: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Status</span><span class="dt-column-order"></span></th>
                                    <th scope="col" data-dt-column="9" rowspan="1" colspan="1" class="dt-orderable-asc dt-orderable-desc" aria-label="Action: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Action</span><span class="dt-column-order"></span></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr ng-repeat="item in students">
                                    <td class="sorting_1">
                                       <div class="form-check style-check d-flex align-items-center">
                                          <input class="form-check-input" type="checkbox">
                                          <label class="form-check-label">
                                          01
                                          </label>
                                       </div>
                                    </td>
                                    <td><span class="text-primary-600">AD52365</span></td>
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
                                    
                                    <td> <span class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span></td>
                                    <td>
                                       <div class="btn-group">
                                          <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                             <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                          </button>
                                          <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                             <li>
                                                <a href="teacher-list.html" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                                <i class="ri-user-3-line"></i>
                                                View Teacher
                                                </a>
                                             </li>
                                             <li>
                                                <a href="edit-student.html" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
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
                                                <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                                <i class="ri-error-warning-line"></i>
                                                Inactive
                                                </button>
                                             </li>
                                             <li>
                                                <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6" data-bs-toggle="modal" data-bs-target="#exampleModalDelete"><i class="ri-delete-bin-6-line"></i>Delete</button>
                                             </li>
                                          </ul>
                                       </div>
                                    </td>
                                 </tr>
                              </tbody>
                              <tfoot></tfoot>
                           </table>
                        </div>
                     </div>
                     <div class="dt-layout-row">
                        <div class="dt-layout-cell dt-start ">
                           <div class="dt-info" aria-live="polite" id="dataTable_info" role="status">Showing 1 to 1 of 1 entry (filtered from 12 total entries)</div>
                        </div>
                        <div class="dt-layout-cell dt-end ">
                           <div class="dt-paging paging_full_numbers"><button class="dt-paging-button disabled first" role="link" type="button" aria-controls="dataTable" aria-disabled="true" aria-label="First" data-dt-idx="first" tabindex="-1">«</button><button class="dt-paging-button disabled previous" role="link" type="button" aria-controls="dataTable" aria-disabled="true" aria-label="Previous" data-dt-idx="previous" tabindex="-1">‹</button><button class="dt-paging-button current" role="link" type="button" aria-controls="dataTable" aria-current="page" data-dt-idx="0" tabindex="0">1</button><button class="dt-paging-button disabled next" role="link" type="button" aria-controls="dataTable" aria-disabled="true" aria-label="Next" data-dt-idx="next" tabindex="-1">›</button><button class="dt-paging-button disabled last" role="link" type="button" aria-controls="dataTable" aria-disabled="true" aria-label="Last" data-dt-idx="last" tabindex="-1">»</button></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

@endsection