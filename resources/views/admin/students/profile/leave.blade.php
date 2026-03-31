<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
   <div
      class="card-header border-bottom bg-base py-10 px-20 d-flex align-items-center justify-content-between">
      <h6 class="text-lg fw-semibold mb-0">Leave </h6>
      <button type="button"
         class="apply-leave-btn btn btn-primary-600 d-flex align-items-center gap-6 py-8 text-sm">
      <span class="d-flex text-sm">
      <i class="ri-calendar-close-line"></i>
      </span>
      Apply Leave
      </button>
   </div>
   <div class="card-body p-0 dataTable-wrapper d-none">
      <div
         class="d-flex flex-wrap align-items-center gap-24 justify-content-between px-20 py-12">
         <div class="d-flex flex-wrap align-items-center gap-16">
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
         <div class="d-flex align-items-center gap-8 text-secondary-light">
            <span class="">
            Rows per page:
            </span>
            <div class="dt-length">
               <select name="dataTable_length" aria-controls="dataTable"
                  class="dt-input form-control form-select">
                  <option value="5">5</option>
                  <option value="10" selected>10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
               </select>
            </div>
         </div>
      </div>
      <table class="table bordered-table mb-0 table-heading-dark-mode w-100 data-table"
         id="dataTable" data-page-length='10'>
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
               <th scope="col">Leave Type</th>
               <th scope="col">Date</th>
               <th scope="col">Duration</th>
               <th scope="col">Apply Date</th>
               <th scope="col">Status</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     01
                     </label>
                  </div>
               </td>
               <td>Medical Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>1</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-success-100 text-success-600 px-20 py-4 radius-4 fw-medium text-sm">Approved</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     02
                     </label>
                  </div>
               </td>
               <td>Special Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>3</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-warning-100 text-warning-600 px-20 py-4 radius-4 fw-medium text-sm">Pending</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     03
                     </label>
                  </div>
               </td>
               <td>Medical Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>5</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-success-100 text-success-600 px-20 py-4 radius-4 fw-medium text-sm">Approved</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     04
                     </label>
                  </div>
               </td>
               <td>Casual Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>6</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-warning-100 text-warning-600 px-20 py-4 radius-4 fw-medium text-sm">Pending</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     05
                     </label>
                  </div>
               </td>
               <td>Medical Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>1</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-success-100 text-success-600 px-20 py-4 radius-4 fw-medium text-sm">Approved</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     06
                     </label>
                  </div>
               </td>
               <td>Special Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>2</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-danger-100 text-danger-600 px-20 py-4 radius-4 fw-medium text-sm">Rejected</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     07
                     </label>
                  </div>
               </td>
               <td>Medical Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>5</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-success-100 text-success-600 px-20 py-4 radius-4 fw-medium text-sm">Approved</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     08
                     </label>
                  </div>
               </td>
               <td>Casual Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>6</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-danger-100 text-danger-600 px-20 py-4 radius-4 fw-medium text-sm">Rejected</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     09
                     </label>
                  </div>
               </td>
               <td>Medical Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>1</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-success-100 text-success-600 px-20 py-4 radius-4 fw-medium text-sm">Approved</span>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="form-check style-check d-flex align-items-center">
                     <input class="form-check-input" type="checkbox">
                     <label class="form-check-label">
                     10
                     </label>
                  </div>
               </td>
               <td>Special Leave</td>
               <td>07 May 2025 - 08 may 2025</td>
               <td>2</td>
               <td>07 May 2025 </td>
               <td>
                  <span
                     class="bg-danger-100 text-danger-600 px-20 py-4 radius-4 fw-medium text-sm">Rejected</span>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
</div>