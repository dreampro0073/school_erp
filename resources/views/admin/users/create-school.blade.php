@extends('admin.layout')

@section('main')
<div ng-controller="schoolCreatePageCtrl">
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0">Add School</h6>
         <p class="text-neutral-600 mt-4 mb-0">Create a new school account.</p>
      </div>
      <a href="{{ route('super-admin.users.type', ['type' => 'schools']) }}" class="btn btn-primary-600 d-inline-flex align-items-center gap-2">
         <i class="ri-arrow-left-line"></i>
         Back
      </a>
   </div>

   <div class="card">
      <div class="card-body">
         <form ng-submit="createSchool()" novalidate>
            <div class="row gy-3">
               <div class="col-md-6">
                  <label class="form-label">School Name</label>
                  <input type="text" class="form-control" ng-model="school.name" required>
               </div>
               <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" ng-model="school.email" required>
               </div>
               <div class="col-md-6">
                  <label class="form-label">ERP ID (optional)</label>
                  <input type="text" class="form-control" ng-model="school.erp_id">
               </div>
               <div class="col-md-6">
                  <label class="form-label">Password</label>
                  <input type="password" class="form-control" ng-model="school.password" required>
               </div>
            </div>

            <div class="mt-24 d-flex align-items-center gap-2">
               <button type="submit" class="btn btn-success-600" ng-disabled="schoolSubmitting">
                  @{{ schoolSubmitting ? 'Saving...' : 'Create School' }}
               </button>
               <a href="{{ route('super-admin.users.type', ['type' => 'schools']) }}" class="btn btn-light">Cancel</a>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection

@section('footer_scripts')
<script>
   (function () {
      if (typeof angular === 'undefined') return;

      angular.module('app').controller('schoolCreatePageCtrl', ['$scope', '$http', function ($scope, $http) {
         $scope.school = { name: '', email: '', erp_id: '', password: '' };
         $scope.schoolSubmitting = false;

         $scope.createSchool = function () {
            if (!$scope.school.name || !$scope.school.email || !$scope.school.password) {
               alert('Please fill all required fields.');
               return;
            }

            $scope.schoolSubmitting = true;
            $http({
               method: 'POST',
               url: base_url + '/super-admin/schools',
               data: $scope.school,
               headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
            }).then(function (response) {
               $scope.schoolSubmitting = false;
               if (response.data && response.data.success) {
                  window.location.href = "{{ route('super-admin.users.type', ['type' => 'schools']) }}";
               } else {
                  alert((response.data && response.data.message) || 'Unable to create school.');
               }
            }).catch(function (error) {
               $scope.schoolSubmitting = false;
               var message = (error.data && error.data.message) ? error.data.message : 'Unable to create school.';
               alert(message);
            });
         };
      }]);
   })();
</script>
@endsection
