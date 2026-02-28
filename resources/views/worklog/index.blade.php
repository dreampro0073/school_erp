@extends('admin.layout')

@section('main')
<div class="mt-24">
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h5 class="mb-0">Worklog</h5>
   </div>

   @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
   @endif

   <div class="card mb-16">
      <div class="card-body">
         <form method="POST" action="{{ route('worklog.store') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
               <label class="form-label">Date</label>
               <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" required>
            </div>
            <div class="col-md-7">
               <label class="form-label">Remark</label>
               <input type="text" name="remark" class="form-control" value="{{ old('remark') }}" placeholder="Write worklog remark">
            </div>
            <div class="col-md-2 d-flex align-items-end">
               <button type="submit" class="btn btn-primary w-100">Add</button>
            </div>
         </form>
      </div>
   </div>

   <div class="card h-100">
      <div class="card-body">
         <form method="GET" action="{{ route('worklog.index') }}" class="row g-3 mb-16">
            <div class="col-md-3">
               <label class="form-label">From Date</label>
               <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control">
            </div>
            <div class="col-md-3">
               <label class="form-label">To Date</label>
               <input type="date" name="to_date" value="{{ $toDate }}" class="form-control">
            </div>
            <div class="col-md-3">
               <label class="form-label">User</label>
               <select name="user_id" class="form-control">
                  <option value="">All Users</option>
                  @foreach ($users as $filterUser)
                     <option value="{{ $filterUser->id }}" {{ (string) $selectedUserId === (string) $filterUser->id ? 'selected' : '' }}>
                        {{ $filterUser->name ?? ('User #' . $filterUser->id) }}
                     </option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
               <button type="submit" class="btn btn-primary">Filter</button>
               <a href="{{ route('worklog.index') }}" class="btn btn-light">Reset</a>
            </div>
         </form>

         <div class="table-responsive">
            <table class="table bordered-table mb-0">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Date</th>
                     <th>User</th>
                     <th>Remark</th>
                     <th>Created At</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($worklogs as $index => $item)
                     <tr>
                        <td>{{ ($worklogs->firstItem() ?? 0) + $index }}</td>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->user->name ?? ('User #' . $item->user_id) }}</td>
                        <td>{{ $item->remark ?? '-' }}</td>
                        <td>{{ $item->created_at }}</td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="5" class="text-center py-4">No worklog entries found.</td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>

         <div class="mt-16">
            {{ $worklogs->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
