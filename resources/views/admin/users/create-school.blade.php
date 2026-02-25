@extends('admin.layout')

@section('main')
@php
   $isEditMode = !empty($isEdit);
   $submitUrl = $isEditMode
      ? route('super-admin.schools.update', ['id' => $school->id])
      : route('super-admin.schools.create');
@endphp

<div>
   <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <div>
         <h6 class="fw-semibold mb-0">{{ $isEditMode ? 'Edit School' : 'Add School' }}</h6>
         <p class="text-neutral-600 mt-4 mb-0">
            {{ $isEditMode ? 'Update school account details.' : 'Create a new school account.' }}
         </p>
      </div>
      <a href="{{ route('super-admin.users.type', ['type' => 'schools']) }}" class="btn btn-primary-600 d-inline-flex align-items-center gap-2">
         <i class="ri-arrow-left-line"></i>
         Back
      </a>
   </div>

   <div class="card">
      <div class="card-body">
         @if ($errors->any())
            <div class="alert alert-danger">
               <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                  @endforeach
               </ul>
            </div>
         @endif

         <form method="POST" action="{{ $submitUrl }}" id="schoolForm">
            @csrf
            <div class="row gy-3">
               <div class="col-md-6">
                  <label class="form-label">School Name</label>
                  <input type="text" class="form-control" name="name" value="{{ old('name', $school->name ?? '') }}" required>
               </div>
               <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" name="email" value="{{ old('email', $school->email ?? '') }}" required>
               </div>
            </div>

            <div class="mt-24 d-flex align-items-center gap-2">
               <button type="submit" class="btn btn-success-600">{{ $isEditMode ? 'Update School' : 'Create School' }}</button>
               <a href="{{ route('super-admin.users.type', ['type' => 'schools']) }}" class="btn btn-light">Cancel</a>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
