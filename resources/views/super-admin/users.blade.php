@extends('layout.layout')

@section('main')
<div ng-controller="superAdminUsersCtrl" ng-init="type='{{ $type }}'; init();" class="mt-24">
    <div class="d-flex justify-content-between align-items-center mb-16">
        <h5 class="mb-0"><?php echo ucfirst($type); ?></h5>
        @if($type == "schools")
            <a href="{{ url('/super-admin/school/add') }}" class="btn btn-primary">
                <i class="ri-add-line"></i> Add New
            </a>
        @endif
    </div>

    <div class="card h-100">
        <div class="card-body p-0 dataTable-wrapper">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-16 px-20 py-12 border-bottom border-neutral-200">
                <div class="d-flex flex-wrap align-items-center gap-16">
                    <div class="dropdown">
                        <button type="button" class="px-12 py-5-px border border-neutral-300 radius-8 d-flex align-items-center gap-20 " data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-flex align-items-center gap-1 text-secondary-light text-sm">
                                <i class="ri-file-upload-line text-md line-height-1"></i>
                                Export
                            </span>
                            <span class=""><i class="ri-arrow-down-s-line"></i></span>
                        </button>
                        <ul class="dropdown-menu p-12 border bg-base shadow">
                            <li>
                                <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                                    <i class="ri-file-3-line"></i>PDF
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                                    <i class="ri-file-excel-line"></i>
                                    Excel
                                </button>
                            </li>
                        </ul>
                    </div>
                    <form class="navbar-search dt-search m-0">
                        <input type="text" class="dt-input bg-transparent radius-4 form-control" aria-controls="dataTable" name="search" placeholder="Search..." ng-model="filter.search" ng-change="onSearch();">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                </div>
                <div class="d-flex align-items-center gap-8 text-secondary-light">
                    <span class="">Rows per page:</span>
                    <div class="dt-length">
                        <select name="dataTable_length" aria-controls="dataTable" class="dt-input form-control form-select" ng-change="init();" ng-model="filter.limit" convert-to-number>
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
                                        <span class="dt-column-title" role="button">
                                            <div class="form-check style-check d-flex align-items-center">
                                                <input class="form-check-input" type="checkbox">
                                                <label class="form-check-label">S.N</label>
                                            </div>
                                        </span>
                                        <span class="dt-column-order"></span>
                                    </th>
                                    <th>
                                        <span class="dt-column-title" role="button">Name</span><span class="dt-column-order"></span>
                                    </th>                                    
                                    <th>
                                        <span class="dt-column-title" role="button">School Name</span><span class="dt-column-order"></span>
                                    </th>
                                    <th>
                                        <span class="dt-column-title" role="button">Email</span><span class="dt-column-order"></span>
                                    </th>
                                    <th>
                                        <span class="dt-column-title" role="button">Mobile</span><span class="dt-column-order"></span>
                                    </th>
                                    <th>
                                        <span class="dt-column-title" role="button">Address</span><span class="dt-column-order"></span>
                                    </th>
                                    <th>
                                        <span class="dt-column-title" role="button">Action</span><span class="dt-column-order"></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in dataSet track by $index">
                                    <td>
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox">
                                            <label class="form-check-label">@{{$index+1}}</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/thumbs/avatar-img1.png" alt="Image" class="flex-shrink-0 me-12 radius-8">
                                            <div class="">
                                                <h6 class="text-md mb-0 fw-medium flex-grow-1">@{{item.name}}</h6>
                                            </div>
                                        </div>
                                    </td>                                    

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/thumbs/avatar-img1.png" alt="Image" class="flex-shrink-0 me-12 radius-8">
                                            <div class="">
                                                <h6 class="text-md mb-0 fw-medium flex-grow-1">@{{item.school_name}}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>@{{item.email}}</td>
                                    <td>@{{item.mobile}}</td>
                                    <td>@{{item.address}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                                <li>
                                                    <a href="{{ url('/super-admin/school/add/') }}/@{{item.id}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                                        <i class="ri-edit-2-line"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                                        <i class="ri-error-warning-line"></i>
                                                        Inactive
                                                    </button>
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
            <modern-pagination  current-page="currentPage" total-pages="totalPages" total-records="totalRecords" on-page-change="changePage(page)">
            </modern-pagination>
        </div>
    </div>
</div>
@endsection
