@extends('layout.layout')

@section('main')
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div class="">
         <h1 class="fw-semibold mb-4 h6 text-primary-light">Teachers List </h1>
         <div class="">
              <a href="{{url('admin/dashboard')}}" class="text-secondary-light hover-text-primary hover-underline">Dashboard </a>
              <span class="text-secondary-light">/ Teachers </span>
         </div>
      </div>
      <a href="{{url('admin/teachers/add')}}" class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6">
         <span class="d-flex text-md">
              <i class="ri-add-large-line"></i>
         </span>
         Add Teacher
      </a>
   </div>

   <div ng-controller="teacherCtrl" ng-init="init();">
      <div class="card h-100">
         <div class="card-body p-0 dataTable-wrapper">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-16 px-20 py-12 border-bottom border-neutral-200">
               <div class="d-flex flex-wrap align-items-center gap-16 flex-grow-1">
                  <form class="navbar-search dt-search m-0 flex-shrink-0">
                     <input type="text" class="dt-input bg-transparent radius-4 form-control" placeholder="Search by name, mobile, email" ng-model="filters.search" ng-change="onSearch();">
                     <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                  </form>

                  <div class="d-flex flex-wrap align-items-center gap-12 teacher-filter-group">
                     <select class="form-select teacher-filter-select" ng-model="filters.gender" ng-change="applyFilters()">
                        <option value="">All Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                     </select>

                     <select class="form-select teacher-filter-select" ng-model="filters.status" ng-change="applyFilters()">
                        <option value="">All Status</option>
                        <option value="0">Active</option>
                        <option value="1">Inactive</option>
                     </select>

                     <button type="button" class="btn btn-outline-secondary btn-sm flex-shrink-0" ng-click="resetFilters()">
                        Clear
                     </button>
                  </div>
               </div>

               <div class="d-flex align-items-center gap-8 text-secondary-light flex-shrink-0">
                  <span class="">
                     Rows per page:
                  </span>
                  <div class="dt-length">
                     <select name="dataTable_length" aria-controls="dataTable" class="dt-input form-control form-select teacher-length-select" ng-change="init();" ng-model="filters.limit" convert-to-number>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                     </select>
                  </div>
               </div>
            </div>

            <div id="dataTable_wrapper" class="dt-container">
               <div class="dt-layout-row dt-layout-table">
                  <div class="dt-layout-cell">
                     <table class="table bordered-table mb-0 data-table dataTable" style="width: 1260.47px;">
                        <thead>
                           <tr role="row">
                              <th>
                                 <span class="dt-column-title" role="button">S.L</span>
                                 <span class="dt-column-order"></span>
                              </th>
                              <th>
                                 <span class="dt-column-title" role="button">Name</span><span class="dt-column-order"></span>
                              </th>
                              <th>
                                 <span class="dt-column-title" role="button">DOB</span><span class="dt-column-order"></span>
                              </th>
                              <th>
                                 <span class="dt-column-title" role="button">Gender</span><span class="dt-column-order"></span>
                              </th>
                              <th>
                                 <span class="dt-column-title" role="button">Mobile Number</span><span class="dt-column-order"></span>
                              </th>
                              <th>
                                 <span class="dt-column-title" role="button">Email</span><span class="dt-column-order"></span>
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
                           <tr ng-repeat="item in teachers track by $index">
                              <td>
                                 <span>@{{ (currentPage - 1) * filters.limit + $index + 1 }}</span>
                              </td>
                              <td>
                                 <div class="">
                                    <h6 class="text-md mb-0 fw-medium flex-grow-1">@{{item.first_name || item.name || '-' }} @{{item.last_name || ''}}</h6>
                                 </div>
                              </td>
                              <td>@{{item.dob || '-'}}</td>
                              <td>@{{item.gender || '-'}}</td>
                              <td>@{{item.mobile || '-'}}</td>
                              <td>@{{item.email || '-'}}</td>
                              <td>
                                 <span class="px-24 py-4 radius-4 fw-medium text-sm" ng-class="item.active == 1 ? 'bg-danger-100 text-danger-600' : 'bg-success-100 text-success-600'">
                                    @{{item.active == 0 ? 'Active' : 'Inactive'}}
                                 </span>
                              </td>
                              <td>
                                 <div class="btn-group">
                                    <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                       <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                       <li>
                                          <a ng-if="item.unique_id" ng-href="@{{ baseUrl + '/admin/teachers/add/' + item.unique_id }}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                          <i class="ri-edit-2-line"></i>
                                          Edit
                                          </a>
                                       </li>
                                    </ul>
                                 </div>
                              </td>
                           </tr>
                           <tr ng-if="!teachers.length">
                              <td colspan="8" class="text-center py-4 text-secondary">No teachers found.</td>
                           </tr>
                        </tbody>
                        <tfoot></tfoot>
                     </table>
                  </div>
               </div>

               <modern-pagination
                  current-page="currentPage"
                  total-pages="totalPages"
                  total-records="totalRecords"
                  on-page-change="changePage(page)">
               </modern-pagination>
            </div>
         </div>
      </div>
   </div>
@endsection
