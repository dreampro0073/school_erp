<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="text-lg fw-semibold mb-0">Schedule</h6>
        </div>

        <button type="button" class="my-sidebar-btn btn btn-primary-600 d-flex align-items-center gap-6">
          <span class="d-flex text-md">
              <i class="ri-add-large-line"></i>
          </span>
          Update Schedule
      </button>
    </div>
    <div class="card-body p-20">
        <div class="table-responsive">
         <table class="table bordered-table mb-0">
            <thead>
               <tr>
                  <th>SN.</th>
                  <th>Classes</th>
                  <th>Session</th>
                  <th>Verifed</th>
                  <th>#</th>
               </tr>
            </thead>
            <tbody>
               <tr ng-repeat="item in classes">
                  <td>@{{$index + 1}}</td>
                  <td>@{{item.name}}</td>
                  <td>
                     <span ng-if="item.status != 1" class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                     <span ng-if="item.status == 1" class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                  </td>
                  <td>
                     <button type="button" class="btn btn-sm btn-info-100 text-info-600 me-8" ng-click="openEditModal()">Edit</button>
                     <button type="button" class="btn btn-sm btn-info-100 text-info-600 me-8" ng-click="openEditModal()">Delete</button>
                  </td>
               </tr>
               <tr ng-if="!standards.length">
                  <td colspan="4" class="text-center py-4">No standards found.</td>
               </tr>
            </tbody>
         </table>
      </div> 
    </div>
</div>




<div class="my-sidebar bg-white position-fixed end-0 top-0 h-100vh overflow-y-auto z-99 max-w-700-px w-100 translate-x-full duration-300 active-translate-0">

    <div class="px-20 py-12 border-bottom d-flex align-items-center justify-content-between gap-20">
        <h5 class="text-lg mb-0">Add New Class</h5>
        <button type="button" class="close-my-sidebar text-danger-600 text-lg d-flex">
            <i class="ri-close-large-line"></i>
        </button>
    </div>

    <form ng-submit="submitForm()" class="d-flex flex-column p-20">

        <div class="row g-3">

            <!-- Class Name -->
            <div class="col-sm-12">
                <label class="text-sm fw-semibold text-primary-light mb-8">Class Name</label>
                <input type="text"
                       class="form-control"
                       ng-model="formData.class_name"
                       placeholder="Enter Class name"
                       required>
            </div>

            <!-- Standard Dropdown -->
            <div class="col-sm-12">
                <label class="text-sm fw-semibold text-primary-light mb-8">Class</label>
                <select class="form-control form-select"
                        ng-model="formData.standard_id"
                        ng-options="std.id as std.name for std in standards"
                        required>
                    <option value="">Select Class</option>
                </select>
            </div>

            <!-- Section Dropdown -->
            <div class="col-sm-12">
                <label class="text-sm fw-semibold text-primary-light mb-8">Section</label>
                <select class="form-control form-select"
                        ng-model="formData.section_id"
                        ng-options="sec.id as sec.name for sec in sections"
                        required>
                    <option value="">Select Section</option>
                </select>
            </div>

            <!-- Session Dropdown -->
            <div class="col-sm-12">
                <label class="text-sm fw-semibold text-primary-light mb-8">Session</label>
                <select class="form-control form-select"
                        ng-model="formData.session_id"
                        ng-options="ses.id as ses.name for ses in sessions"
                        required>
                    <option value="">Select Session</option>
                </select>
            </div>

            <!-- Status -->
            <div class="col-sm-12">
                <label class="text-sm fw-semibold text-primary-light mb-8">Status</label>
                <select class="form-control form-select"
                        ng-model="formData.status"
                        required>
                    <option value="">Select Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-12">
                <div class="d-flex justify-content-center gap-3 mt-8">
                    <button type="button"
                            ng-click="resetForm()"
                            class="border border-danger-600 text-danger-600 px-50 py-11 radius-8">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary-600 px-28 py-12 radius-8 w-100">
                        Save
                    </button>
                </div>
            </div>

        </div>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $('.my-sidebar-btn').on('click', function () {
        $('.my-sidebar').addClass('active');
        $('.overlay').addClass('active');
    });
    $('.close-my-sidebar, .overlay').on('click', function () {
        $('.my-sidebar').removeClass('active');
        $('.overlay').removeClass('active');
    });


    $('.edit-sidebar-btn').on('click', function () {
        $('.edit-sidebar').addClass('active');
        $('.overlay').addClass('active');
    });
    $('.close-edit-sidebar, .overlay').on('click', function () {
        $('.edit-sidebar').removeClass('active');
        $('.overlay').removeClass('active');
    });


</script>