<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="text-lg fw-semibold mb-0">Schedule</h6>
            <button class="btn btn-primary btn-sm" ng-click="addRow()">
                + Add Row
            </button>
        </div>
    </div>
    <div class="card-body p-20">
        <div class="table-responsive">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Standard</th>
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Duration</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-repeat="row in scheduleRows track by $index">

                        <td>
                            <select ng-model="row.day_id" class="form-control">
                                <option value="">Select</option>
                                <option ng-repeat="d in days" value="@{{d.id}}">
                                    @{{d.name}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <select ng-model="row.standard_id" class="form-control">
                                <option value="">Select</option>
                                <option ng-repeat="s in standards" value="@{{s.id}}">
                                    @{{s.name}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <select ng-model="row.section_id" class="form-control">
                                <option value="">Select</option>
                                <option ng-repeat="sec in sections" value="@{{sec.id}}">
                                    @{{sec.name}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <select ng-model="row.subject_id" class="form-control">
                                <option value="">Select</option>
                                <option ng-repeat="sub in subjects" value="@{{sub.id}}">
                                    @{{sub.name}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <select ng-model="row.teacher_id" class="form-control">
                                <option value="">Select</option>
                                <option ng-repeat="t in teachers" value="@{{t.id}}">
                                    @{{t.name}}
                                </option>
                            </select>
                        </td>

                        <td>
                            <input type="time" ng-model="row.start_time" class="form-control" ng-change="calculateDuration(row)">
                        </td>

                        <td>
                            <input type="time" ng-model="row.end_time" class="form-control" ng-change="calculateDuration(row)">
                        </td>
                        <td>
                            <input type="text" ng-model="row.duration" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" ng-model="row.remarks" class="form-control">
                        </td>

                        <td>
                            <button class="btn btn-danger btn-sm" ng-click="removeRow($index)"> Delete </button>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <button class="btn btn-success" ng-click="saveSchedule()">
                Save Schedule
            </button>
        </div>
    </div>
</div>


