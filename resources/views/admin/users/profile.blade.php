@extends('admin.layout')

@section('main')
<div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
   <div>
      <h6 class="fw-semibold mb-0">User Profile</h6>
      <p class="text-neutral-600 mt-4 mb-0">Role: {{ $roleLabel }}</p>
   </div>
   <div class="d-flex gap-2">
      <a href="{{ url()->previous() }}" class="btn btn-light">Back</a>
   </div>
</div>

<div class="card">
   <div class="card-body">
      <div class="row g-3">
         <div class="col-md-4"><strong>ID:</strong> {{ $user->id ?? '-' }}</div>
         <div class="col-md-4"><strong>ERP ID:</strong> {{ $user->erp_id ?? '-' }}</div>
         <div class="col-md-4"><strong>Status:</strong> {{ ((int) ($user->active ?? 0) === 1) ? 'Active' : 'Inactive' }}</div>
         <div class="col-md-6"><strong>Name:</strong> {{ $user->name ?? '-' }}</div>
         <div class="col-md-6"><strong>Email:</strong> {{ $user->email ?? '-' }}</div>
         @if (isset($user->mobile))
            <div class="col-md-6"><strong>Mobile:</strong> {{ $user->mobile ?: '-' }}</div>
         @endif
         <div class="col-md-6"><strong>Created At:</strong> {{ $user->created_at ?? '-' }}</div>
         @if (isset($user->updated_at))
            <div class="col-md-6"><strong>Updated At:</strong> {{ $user->updated_at ?? '-' }}</div>
         @endif
      </div>
   </div>
</div>
@endsection
