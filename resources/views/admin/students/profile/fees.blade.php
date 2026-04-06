<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden dt-container">
	<div
		class="card-header border-bottom bg-base py-10 px-20 d-flex align-items-center justify-content-between">
		<h6 class="text-lg fw-semibold mb-0">Fees </h6>
		<button ng-click="openFeeModal()" type="button"
			class="collect-fees-btn btn btn-primary-600 d-flex align-items-center gap-6 py-8 text-sm">
			<span class="d-flex text-sm">
				<i class="ri-calendar-close-line"></i>
			</span>
			Collect Fees
		</button>
	</div>
	<div class="card-body p-0 dataTable-wrapper" style="overflow-x: scroll;">
		<div class="p-20 d-none">
			<div class="row g-3">
				<div class="col-xl-3 col-sm-6">
					<div class="card px-20 py-28 shadow-2 radius-8 h-100 border border-neutral-200 shadow-none gradient-bg-end-10">
						<div class="card-body p-0">
							<div class="d-flex flex-wrap align-items-center justify-content-between gap-1">
								<div>
									<h6 class="fw-semibold mb-2">$10,500</h6>
									<span class="fw-medium text-secondary-light text-sm">Total
									Amount</span>
								</div>
								<span class="mb-0 w-48-px h-48-px bg-info-600 text-white flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
									<img src="assets/images/icons/fees-icon1.png" alt="Clock Icon">
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6">
					<div class="card px-20 py-28 shadow-2 radius-8 h-100 border border-neutral-200 shadow-none gradient-bg-end-8">
						<div class="card-body p-0">
							<div class="d-flex flex-wrap align-items-center justify-content-between gap-1">
								<div>
									<h6 class="fw-semibold mb-2">$200</h6>
									<span class="fw-medium text-secondary-light text-sm">Total
									Fine</span>
								</div>
								<span class="mb-0 w-48-px h-48-px bg-danger-600 text-white flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
									<img src="assets/images/icons/fees-icon2.png"alt="Absent Icon">
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6">
					<div class="card px-20 py-28 shadow-2 radius-8 h-100 border border-neutral-200 shadow-none gradient-bg-end-7">
						<div class="card-body p-0">
							<div
								class="d-flex flex-wrap align-items-center justify-content-between gap-1">
								<div>
									<h6 class="fw-semibold mb-2">$7,500</h6>
									<span class="fw-medium text-secondary-light text-sm">Total
									Paid </span>
								</div>
								<span
									class="mb-0 w-48-px h-48-px bg-success-600 text-white flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
								<img src="assets/images/icons/fees-icon3.png"
									alt="Present Icon">
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6">
					<div class="card px-20 py-28 shadow-2 radius-8 h-100 border border-neutral-200 shadow-none gradient-bg-end-11">
						<div class="card-body p-0">
							<div
								class="d-flex flex-wrap align-items-center justify-content-between gap-1">
								<div>
									<h6 class="fw-semibold mb-2">$3,000</h6>
									<span class="fw-medium text-secondary-light text-sm">Total
									Due</span>
								</div>
								<span
									class="mb-0 w-48-px h-48-px bg-orange text-white flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
									<img src="assets/images/icons/fees-icon4.png"
									alt="Holiday Icon">
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div
			class="d-flex flex-wrap align-items-center gap-24 justify-content-between px-20 pb-16">
			<div class="align-items-center gap-16" style="display: none;">
			<!-- <div class="d-flex flex-wrap align-items-center gap-16" > -->
				<form class="navbar-search dt-search m-0">
					<input type="text" class="dt-input bg-transparent radius-4"
						aria-controls="dataTable" name="search" placeholder="Search...">
					<iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
				</form>
				<div class="">
					<select class="form-control form-select">
						<option value="Year 2025/2026">Year 2025/2026</option>
						<option value="Year 2026/2027">Year 2026/2027</option>
						<option value="Year 2027/2028">Year 2027/2028</option>
						<option value="Year 2028/2029">Year 2028/2029</option>
					</select>
				</div>
				<div class="dropdown">
					<button type="button"
						class="px-12 py-5-px border border-neutral-300 radius-8 d-flex align-items-center gap-20 "
						data-bs-toggle="dropdown" aria-expanded="false">
					<span
						class="d-flex align-items-center gap-1 text-secondary-light text-sm">
					<i class="ri-file-upload-line text-md line-height-1"></i>
					Export
					</span>
					<span class="">
					<i class="ri-arrow-down-s-line"></i>
					</span>
					</button>
					<ul class="dropdown-menu p-12 border bg-base shadow">
						<li>
							<button type="button"
								class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10"
								data-bs-toggle="modal" data-bs-target="#exampleModalView">
							<i class="ri-file-3-line"></i>
							PDF
							</button>
						</li>
						<li>
							<button type="button"
								class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10"
								data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
								<i class="ri-file-excel-line"></i>
								Excel
							</button>
						</li>
					</ul>
				</div>
			</div>
			<div style="display:none;">
				<div class="d-flex align-items-center gap-8 text-secondary-light">
					<span class="">
						Rows per page:
					</span>
					<div class="dt-length">
						<select name="dataTable_length" aria-controls="dataTable" class="dt-input form-control form-select">
							<option value="5">5</option>
							<option value="10" selected>10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					</div>
				</div>
			</div>
		  
		</div>
		<table class="table bordered-table mb-0 table-heading-dark-mode w-100 data-table"
			id="dataTableTwo" data-page-length='10'>
			<thead>
				<tr>
					<th scope="col">
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input" type="checkbox">
							<label class="form-check-label">
							S.L
							</label>
						</div>
					</th>
					<th scope="col">Fees Type</th>
					<th scope="col">Amount</th>

					<th scope="col">Payment Mode</th>
					<th scope="col">Paid Date</th>
					<th scope="col">Status</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="item in payments track by $index">
					<td>
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input" type="checkbox">
							<label class="form-check-label">@{{$index+1}}</label>
						</div>
					</td>
					<td>
						@{{item.fee_type_name}}  <span ng-show="item.month_name">(@{{item.month_name}})</span>
					</td>
					<td>
						@{{item.amount}}
					</td>
					<td>
						@{{item.payment_mode}}
					</td>
					<td>
						 @{{item.paid_date}}
					</td>
					<td>
						<span
							class="bg-success-100 text-success-600 px-20 py-4 radius-4 fw-medium text-sm">
							Paid
						</span>
					</td>
				</tr>
				
			</tbody>
		</table>
		<modern-pagination
			current-page="currentPage"
			total-pages="totalPages"
			total-records="totalRecords"
			on-page-change="changePage(page)"
		></modern-pagination>
	</div>
</div>

<div class="my-sidebar theme-bg-white position-fixed end-0 top-0 h-100vh overflow-y-auto z-99 max-w-700-px w-100 translate-x-full duration-300" ng-class="{'active active-translate-0': isSidebarOpen}" style="z-index: 9999!;">
	<div class="px-20 py-12 border-bottom d-flex align-items-center justify-content-between gap-20">
		<h5 class="text-lg fw-semibold mb-0">@{{ isEditMode ? 'Edit Payment' : 'Add New Payment' }}</h5>
		<button type="button" class="close-my-sidebar text-danger-600 text-lg d-flex" ng-click="closeSidebar()">
			<i class="ri-close-large-line"></i>
		</button>
	</div>

	<form name="myForm" novalidate="novalidate" ng-submit="collectFees(myForm.$valid)" class="d-flex flex-column p-20">
		<div class="row g-3">
			<div class="col-sm-12 form-group">
				<label class="text-sm fw-semibold text-primary-light mb-8">Fee Type <span class="text-danger-600">* </span></label>
				<select class="form-select" ng-model="formData.fee_type_id" convert-to-number ng-change="onChangeFeeType()" required>
					<option value="">Select</option>
					<option value="@{{item.value}}" ng-repeat="item in fee_types">@{{item.label}}</option>
				</select>
			</div>
			<div class="col-sm-12 form-group" ng-if="formData.fee_type_id == 2 || formData.fee_type_id == 3">
				<label class="text-sm fw-semibold text-primary-light mb-8">Fee Frequencies <span class="text-danger-600">* </span></label>
				<select class="form-select" ng-model="formData.frequency_id" convert-to-number ng-change="getFeeSubs();" required>
					<option value="">Select</option>
					<option value="@{{item.value}}" ng-repeat="item in fee_frequencies">@{{item.label}}</option>
				</select>
			</div>

			<div class="col-sm-12 form-group" ng-if="(formData.fee_type_id == 2 && formData.frequency_id == 1) || (formData.fee_type_id == 3 && formData.frequency_id == 1)">
				<label class="text-sm fw-semibold text-primary-light mb-8">Month</label>
				<select class="form-select" ng-model="formData.month" convert-to-number required>
					<option value="">Select</option>
					<option value="@{{item.value}}" ng-repeat="item in months">@{{item.label}}</option>
				</select>
			</div>

			<div class="col-sm-12 form-group">
				<label class="text-sm fw-semibold text-primary-light mb-8">Amount <span class="text-danger-600">* </span></label>
				<input type="text" class="form-control" ng-model="formData.amount" readonly required>

			</div>

			<div class="col-sm-12 form-group">
				<label class="text-sm fw-semibold text-primary-light mb-8">Payment Mode <span class="text-danger-600">* </span></label>
				<select class="form-select" ng-model="formData.payment_mode" convert-to-number required>
					<option value="">Select</option>
					<option value="@{{item.value}}" ng-repeat="item in payment_modes">@{{item.label}}</option>
				</select>
			</div>

			<div class="col-sm-12 form-group" ng-if="formData.payment_mode != 6">
				<label class="text-sm fw-semibold text-primary-light mb-8">Transction Id</label>
				<input type="text" class="form-control" ng-model="formData.transction_id">

			</div>
			<div class="col-sm-12 form-group" ng-if="formData.payment_mode == 6">
				<label class="text-sm fw-semibold text-primary-light mb-8">Cheque No</label>
				<input type="text" class="form-control" ng-model="formData.cheque_no" >

			</div>
			<div class="col-12">
				<div class="d-flex justify-content-center gap-3 mt-8">
					<button type="button" ng-click="resetForm()" class="border border-danger-600 text-danger-600 px-50 py-11 radius-8"> Cancel </button>

					<button ng-disabled="formData.amount == 0 || formData.collect_fee_loading " type="submit" class="btn btn-primary-600 px-28 py-12 radius-8 w-100">
						<span ng-if="!collect_fee_loading">@{{ isEditMode ? 'Update' : 'Collect' }}</span>

						<div ng-if="collect_fee_loading" class="spinner-grow text-success" role="status">
							
						</div>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>