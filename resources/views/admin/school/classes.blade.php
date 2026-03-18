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