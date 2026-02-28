@extends('admin.layout')

@section('main')
<div ng-controller="expenseEntriesCtrl" ng-init="init();" class="mt-24">
   <div class="card mb-16"><div class="card-body"><form class="row g-3" ng-submit="save()"><div class="col-md-2"><label class="form-label">Master ID</label><input type="number" class="form-control" ng-model="formData.master_id" required></div><div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" ng-model="formData.date" required></div><div class="col-md-2"><label class="form-label">Amount</label><input type="number" step="0.01" class="form-control" ng-model="formData.amount" required></div><div class="col-md-4"><label class="form-label">Remark</label><input type="text" class="form-control" ng-model="formData.remark"></div><div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit">@{{formData.id ? 'Update' : 'Add'}}</button></div></form></div></div>
   <div class="card"><div class="card-body"><table class="table bordered-table mb-0"><thead><tr><th>#</th><th>Date</th><th>Amount</th><th>Remark</th><th>Action</th></tr></thead><tbody><tr ng-repeat="item in rows track by item.id"><td>@{{$index+1}}</td><td>@{{item.date}}</td><td>@{{item.amount}}</td><td>@{{item.remark || '-'}}</td><td><button class="btn btn-sm btn-outline-primary" ng-click="edit(item)">Edit</button></td></tr></tbody></table></div></div>
</div>
@endsection
