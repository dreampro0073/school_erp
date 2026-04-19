@extends('layout.layout')

@section('main')
<div ng-controller="attendanceCtrl" ng-init="init();" class="mt-24 attendance-page">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h1 class="fw-semibold mb-4 h6 text-primary-light">Attendance Management</h1>
         <div>
            <a href="{{ url('admin/dashboard') }}" class="text-secondary-light hover-text-primary hover-underline">Dashboard</a>
            <span class="text-secondary-light">/ Attendance</span>
         </div>
      </div>
      <button type="button" class="btn btn-primary-600 d-flex align-items-center gap-8" ng-click="saveAttendance()" ng-disabled="saving || loading || !attendanceItems.length">
         <i class="ri-save-line"></i>
         <span>@{{ saving ? 'Saving...' : 'Save Attendance' }}</span>
      </button>
   </div>

   <div class="row g-4 mb-24">
      <div class="col-xxl-3 col-lg-4">
         <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-24">
               <div class="d-flex align-items-center gap-12 mb-16">
                  <div class="w-48-px h-48-px radius-12 bg-primary-50 text-primary-600 d-flex align-items-center justify-content-center text-xl">
                     <i class="ri-filter-3-line"></i>
                  </div>
                  <div>
                     <h6 class="mb-4">Filters</h6>
                     <p class="mb-0 text-sm text-secondary-light">Match the demo flow inside the live portal.</p>
                  </div>
               </div>

               <div class="row g-3">
                  <div class="col-12">
                     <label class="form-label">Date</label>
                     <input type="date" class="form-control" ng-model="filters.date" ng-change="loadAttendance()">
                  </div>

                  <div class="col-12">
                     <label class="form-label">Search</label>
                     <input type="text" class="form-control" placeholder="Search by name, mobile, code" ng-model="filters.search" ng-change="loadAttendance()">
                  </div>

                  <div class="col-12">
                     <button type="button" class="btn btn-outline-secondary w-100" ng-click="resetSearch()">Reset Filter</button>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xxl-9 col-lg-8">
         <div class="row g-3">
            <div class="col-sm-6 col-xl-3" ng-repeat="card in summaryCards track by card.code">
               <div class="card border-0 shadow-sm h-100 attendance-summary-card">
                  <div class="card-body p-20">
                     <div class="d-flex align-items-center justify-content-between gap-12">
                        <div>
                           <p class="text-sm text-secondary-light mb-8">@{{ card.label }}</p>
                           <h4 class="mb-0">@{{ card.count }}</h4>
                        </div>
                        <span class="w-48-px h-48-px rounded-circle d-flex align-items-center justify-content-center attendance-summary-icon" ng-class="card.badge_class">
                           <i class="ri-user-follow-line"></i>
                        </span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-12">
               <div class="card border-0 shadow-sm">
                  <div class="card-body p-20 p-lg-24">
                     <div class="d-flex flex-wrap align-items-center justify-content-between gap-16 mb-16">
                        <div>
                           <h5 class="mb-4">Teacher Attendance Sheet</h5>
                           <p class="mb-0 text-sm text-secondary-light">Mark status with the same quick-select style as the demo page.</p>
                        </div>

                        <div class="d-flex align-items-center flex-wrap gap-8">
                           <span class="text-sm text-secondary-light">Date:</span>
                           <span class="px-12 py-8 bg-neutral-100 radius-8 text-sm fw-medium">@{{ filters.date }}</span>
                        </div>
                     </div>

                     <div class="table-responsive">
                        <table class="table bordered-table align-middle mb-0">
                           <thead>
                              <tr>
                                 <th style="width: 72px;">S.L</th>
                                 <th style="width: 140px;">Code</th>
                                 <th>Name</th>
                                 <th style="width: 180px;">Info</th>
                                 <th style="min-width: 380px;">Attendance</th>
                                 <th style="min-width: 220px;">Note</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr ng-repeat="item in attendanceItems track by item.id">
                                 <td>
                                    <span class="fw-semibold">@{{ $index + 1 }}</span>
                                 </td>
                                 <td>
                                    <span class="text-primary-600 fw-semibold">@{{ item.code }}</span>
                                 </td>
                                 <td>
                                    <div>
                                       <h6 class="text-md mb-4 fw-medium">@{{ item.name }}</h6>
                                       <p class="mb-0 text-sm text-secondary-light">@{{ item.mobile || 'No mobile' }}</p>
                                    </div>
                                 </td>
                                 <td>
                                    <span class="text-sm text-secondary-light">@{{ item.meta || '-' }}</span>
                                 </td>
                                 <td>
                                    <div class="attendance-status-group">
                                       <label class="attendance-status-option" ng-repeat="status in statuses track by status.code" ng-class="{'is-active': item.status === status.code}">
                                          <input type="radio" ng-model="item.status" ng-value="status.code" name="attendance_@{{ item.id }}">
                                          <span>@{{ status.label }}</span>
                                       </label>
                                    </div>
                                 </td>
                                 <td>
                                    <input type="text" class="form-control" placeholder="Write note..." ng-model="item.remark">
                                 </td>
                              </tr>
                              <tr ng-if="!attendanceItems.length && !loading">
                                 <td colspan="6" class="text-center py-5 text-secondary-light">No records found for the selected filters.</td>
                              </tr>
                              <tr ng-if="loading">
                                 <td colspan="6" class="text-center py-5 text-secondary-light">Loading attendance data...</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="card border-0 shadow-sm">
      <div class="card-body p-20 p-lg-24">
         <div class="d-flex flex-wrap align-items-center justify-content-between gap-16 mb-16">
            <div>
               <h5 class="mb-4">Attendance History</h5>
               <p class="mb-0 text-sm text-secondary-light">Recent saved entries with quick filtering.</p>
            </div>
         </div>

         <div class="row g-3 mb-16">
            <div class="col-md-3">
               <label class="form-label">Teacher</label>
               <select class="form-control" ng-model="historyFilter.teacher_id">
                  <option value="">All Teachers</option>
                  <option ng-repeat="teacher in teacherFilters track by teacher.id" ng-value="teacher.id">@{{ teacher.name }}</option>
               </select>
            </div>
            <div class="col-md-3">
               <label class="form-label">From Date</label>
               <input type="date" class="form-control" ng-model="historyFilter.from_date">
            </div>
            <div class="col-md-3">
               <label class="form-label">To Date</label>
               <input type="date" class="form-control" ng-model="historyFilter.to_date">
            </div>
            <div class="col-md-3 d-flex align-items-end">
               <button type="button" class="btn btn-outline-primary w-100" ng-click="loadHistory()">Apply Filter</button>
            </div>
         </div>

         <div class="table-responsive">
            <table class="table bordered-table align-middle mb-0">
               <thead>
                  <tr>
                     <th>Date</th>
                     <th>Type</th>
                     <th>Name</th>
                     <th>Status</th>
                     <th>Remark</th>
                  </tr>
               </thead>
               <tbody>
                  <tr ng-repeat="row in historyRows track by row.id">
                     <td>@{{ row.date }}</td>
                     <td>Teacher</td>
                     <td>@{{ row.name }}</td>
                     <td>
                        <span class="px-12 py-6 radius-8 fw-medium text-sm" ng-class="row.status_badge_class">@{{ row.status_label }}</span>
                     </td>
                     <td>@{{ row.remark || '-' }}</td>
                  </tr>
                  <tr ng-if="!historyRows.length">
                     <td colspan="5" class="text-center py-5 text-secondary-light">No history found.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
@endsection

@section('footer_scripts')
<style>
   .attendance-page .attendance-summary-card {
      background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
   }

   .attendance-page .attendance-summary-icon {
      font-size: 18px;
   }

   .attendance-page .attendance-status-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
   }

   .attendance-page .attendance-status-option {
      position: relative;
      margin: 0;
      cursor: pointer;
   }

   .attendance-page .attendance-status-option input {
      position: absolute;
      opacity: 0;
      pointer-events: none;
   }

   .attendance-page .attendance-status-option span {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 8px 14px;
      border: 1px solid #d7dde4;
      border-radius: 999px;
      background: #fff;
      color: #425466;
      font-size: 13px;
      font-weight: 500;
      transition: all .2s ease;
   }

   .attendance-page .attendance-status-option.is-active span {
      border-color: #2f6fed;
      background: #ecf3ff;
      color: #2f6fed;
      box-shadow: inset 0 0 0 1px #2f6fed;
   }
</style>
@endsection
