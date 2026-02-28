@extends('admin.layout')

@section('main')
<div ng-controller="expensesCtrl" ng-init="init();" class="mt-24">
   <div class="card mb-16"><div class="card-body"><form class="row g-3" ng-submit="save()"><div class="col-md-6"><label class="form-label">Expense Name</label><input type="text" class="form-control" ng-model="formData.name" required></div><div class="col-md-3"><label class="form-label">Status</label><select class="form-control" ng-model="formData.active"><option value="1">Active</option><option value="0">Inactive</option></select></div><div class="col-md-3 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit">@{{formData.id ? 'Update' : 'Add'}} Expense</button></div></form></div></div>
   <div class="card"><div class="card-body"><table class="table bordered-table mb-0"><thead><tr><th>#</th><th>Name</th><th>Status</th><th>Action</th></tr></thead><tbody><tr ng-repeat="item in rows track by item.id"><td>@{{$index+1}}</td><td>@{{item.name}}</td><td>@{{item.active == 0 ? 'Inactive' : 'Active'}}</td><td><button class="btn btn-sm btn-outline-primary" ng-click="edit(item)">Edit</button></td></tr></tbody></table></div></div>
</div>
@endsection
