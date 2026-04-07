<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-20 px-20">
        <h6 class="text-lg fw-semibold mb-20">Schedule</h6>
        <div class="row align-items-center gap-2">
            <div class="col-md-3">
                <select ng-model="schedule.day_id" class="form-select" ng-change="onTypeChange()">
                    <option value="8">Weekly</option>
                    <option ng-repeat="(key, day) in days" value="@{{key}}">
                        @{{day}}
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <select ng-model="schedule.standard_id" class="form-select" ng-change="onTypeChange()">
                    <option value="">Select Standard</option>
                    <option ng-repeat="(key, value) in standards" value="@{{key}}">
                        @{{value}}
                    </option>
                </select>
            </div>
            <div class="col-md-3" ng-if="edit_flag">
                <button ng-click="addRow()" type="button" class="collect-fees-btn btn btn-primary-600 d-flex align-items-center gap-6 py-8 text-sm">
                    <span class="d-flex text-sm">
                        <i class="ri-add"></i>
                    </span>
                    + Add Row
                </button>
            </div>
        </div>
    </div>

    <div class="card-body p-20" ng-if="schedule.day_id > 0 && schedule.standard_id > 0">
        <div ng-if="list_loading" class="mb-3">
            Loading...
        </div>

        <div class="table-responsive">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Duration</th>
                        <th>Remarks</th>
                        <th width="100">#</th>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-if="scheduleRows.length == 0">
                        <td colspan="7" class="text-center">No schedule found</td>
                    </tr>

                    <tr ng-repeat="row in scheduleRows track by $index">

                        <td>
                            <small>@{{days[row.day_id]}}</small>

                            <select ng-model="row.subject_id" class="form-select" ng-disabled="!edit_flag">
                                <option value="">Select Subject</option>
                                <option ng-repeat="sub in subjects" ng-value="sub.id">
                                    @{{sub.name}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <select ng-model="row.teacher_id" class="form-select" ng-disabled="!edit_flag">
                                <option value="">Select Teacher</option>
                                <option ng-repeat="(key, value) in teachers" ng-value="@{{key}}">
                                    @{{value}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <input type="text" ng-model="row.start_time" class="form-control" ng-readonly="!edit_flag" ng-class="{'bg-light': !edit_flag}">
                        </td>

                        <td>
                            <input type="text" ng-model="row.end_time" class="form-control" ng-readonly="!edit_flag" ng-class="{'bg-light': !edit_flag}">
                        </td>

                        <td>
                            <input type="text" ng-model="row.duration" class="form-control" ng-readonly="!edit_flag" ng-class="{'bg-light': !edit_flag}">
                        </td>

                        <td>
                            <input type="text" ng-model="row.remarks" class="form-control" placeholder="Remarks" ng-readonly="!edit_flag" ng-class="{'bg-light': !edit_flag}">
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="removeRow($index)" ng-if="edit_flag">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>

                            <span ng-if="!edit_flag">-</span>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

        <div class="mt-3" ng-if="edit_flag">
            
            <button class="collect-fees-btn btn btn-primary-600 d-flex align-items-center gap-6 py-8 text-sm" ng-click="saveSchedule()">Save Schedule</button>
        </div>
    </div>
</div>


