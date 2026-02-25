@extends('admin.layout')

@section('main')
<div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
   <div>
      <h6 class="fw-semibold mb-0">School Services</h6>
      <p class="text-neutral-600 mt-4 mb-0">Manage services for: <strong>{{ $school->name ?? ('School #' . ($school->id ?? '')) }}</strong></p>
   </div>
   <a href="{{ route('super-admin.users.type', ['type' => 'schools']) }}" class="btn btn-primary-600 d-inline-flex align-items-center gap-2">
      <i class="ri-arrow-left-line"></i>
      Back
   </a>
</div>

<div class="card">
   <div class="card-body">
      @if (session('success'))
         <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if (session('failure'))
         <div class="alert alert-danger">{{ session('failure') }}</div>
      @endif

      <form method="POST" action="{{ route('super-admin.schools.services.save', ['id' => $school->id]) }}">
         @csrf
         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th style="width: 70px;">Enable</th>
                     <th>Service</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($services as $service)
                     @php
                        $sid = (int) $service->id;
                        $isEnabled = !empty($selected[$sid]['enabled']);
                     @endphp
                     <tr>
                        <td>
                           <input type="checkbox" class="form-check-input" name="services[{{ $sid }}][enabled]" value="1" {{ $isEnabled ? 'checked' : '' }}>
                        </td>
                        <td>{{ $service->name }}</td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="2" class="text-center py-4">No services found.</td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
         <div class="mt-20">
            <button type="submit" class="btn btn-success-600">Save Services</button>
         </div>
      </form>
   </div>
</div>
@endsection
