<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="text-lg fw-semibold mb-0">Schedule</h6>
        
            <div class="d-flex align-items-center gap-2">
                <select ng-model="schedule.day_id" class="form-control" ng-change="onTypeChange()">
                    <option value="8">Weekly</option>
                    <option ng-repeat="(key, day) in days" value="@{{key}}">
                        @{{day}}
                    </option>
                </select>

                <select ng-model="schedule.standard_id" class="form-control" ng-change="onTypeChange()">
                    <option value="">Select Standard</option>
                    <option ng-repeat="(key, value) in standards" value="@{{key}}">
                        @{{value}}
                    </option>
                </select>

                <button type="button" class="btn btn-primary btn-sm" ng-click="addRow()"
                    ng-disabled="!schedule.day_id || !schedule.standard_id">
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
                        <th width="100">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-if="scheduleRows.length == 0">
                        <td colspan="8" class="text-center">No schedule found</td>
                    </tr>

                    <tr ng-repeat="row in scheduleRows track by $index">
                        <td>
                            <small>@{{days[row.day_id]}} </small>
                            <select ng-model="row.subject_id" class="form-control">
                                <option value="">Select Subject</option>
                                <option ng-repeat="sub in subjects" ng-value="@{{sub.id}}">
                                    @{{sub.name}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <select ng-model="row.teacher_id" class="form-control">
                                <option value="">Select Teacher</option>
                                <option ng-repeat="(key, value) in teachers" ng-value="@{{key}}">
                                    @{{value}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <input type="text" ng-model="row.start_time" class="form-control" >
                        </td>

                        <td>
                            <input type="text" ng-model="row.end_time" class="form-control">
                        </td>

                        <td>
                            <input type="text" ng-model="row.duration" class="form-control" >
                        </td>

                        <td>
                            <input type="text" ng-model="row.remarks" class="form-control" placeholder="Remarks">
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="removeRow($index)">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <button class="btn btn-success" ng-click="saveSchedule()">Save Schedule</button>
        </div>
    </div>
</div>


