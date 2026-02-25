@extends('admin.layout')

@section('main')
<div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
   <div>
      <h6 class="fw-semibold mb-0">{{ $pageTitle ?? 'Users' }}</h6>
      <p class="text-neutral-600 mt-4 mb-0">{{ $pageSubtitle ?? 'All users list from users table.' }}</p>
   </div>
   <div class="d-flex align-items-center gap-2">
      @if (($currentType ?? '') === 'schools')
         <a href="{{ route('super-admin.schools.create-page') }}" class="btn btn-success-600 d-inline-flex align-items-center gap-2">
            <i class="ri-add-line"></i>
            Add School
         </a>
      @endif
      <a href="{{ url('/super-admin/dashboard') }}" class="btn btn-primary-600 d-inline-flex align-items-center gap-2">
         <i class="ri-arrow-left-line"></i>
         Back
      </a>
   </div>
</div>

<div class="card h-100">
   <div class="card-body p-0">
      @if (session('success'))
         <div class="alert alert-success m-16 mb-0">{{ session('success') }}</div>
      @endif
      @if (session('failure'))
         <div class="alert alert-danger m-16 mb-0">{{ session('failure') }}</div>
      @endif
      <div class="table-responsive">
         <table class="table bordered-table mb-0">
            <thead>
               <tr>
                  <th>#</th>
                  @if (in_array('erp_id', $columns, true))
                     <th>ID</th>
                  @endif
                  @if (in_array('name', $columns, true))
                     <th>Name</th>
                  @endif
                  @if (in_array('email', $columns, true))
                     <th>Email</th>
                  @endif
                  @if (in_array('active', $columns, true))
                     <th>Status</th>
                  @endif
                  @if (in_array('created_at', $columns, true))
                     <th>Created At</th>
                  @endif
                  @if (in_array('id', $columns, true) && in_array('active', $columns, true))
                     <th>Action</th>
                  @endif
               </tr>
            </thead>
            <tbody>
               @forelse ($users as $index => $user)
                  <tr>
                     <td>{{ ($users->firstItem() ?? 0) + $index }}</td>
                     @if (in_array('erp_id', $columns, true))
                        <td>{{ $user->erp_id }}</td>
                     @endif
                     @if (in_array('name', $columns, true))
                        <td>{{ $user->name }}</td>
                     @endif
                     @if (in_array('email', $columns, true))
                        <td>{{ $user->email }}</td>
                     @endif
                     @if (in_array('active', $columns, true))
                        <td>
                           @if ((int) $user->active === 1)
                              <span class="bg-success-100 text-success-600 px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                           @else
                              <span class="bg-danger-100 text-danger-600 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                           @endif
                        </td>
                     @endif
                     @if (in_array('created_at', $columns, true))
                        <td>{{ $user->created_at }}</td>
                     @endif
                     @if (in_array('id', $columns, true) && in_array('active', $columns, true))
                        <td>
                           @if (($currentType ?? '') === 'schools')
                              <a href="{{ route('super-admin.schools.services', ['id' => $user->id]) }}" target="_blank" class="btn btn-sm btn-info-100 text-info-600 me-8">Services</a>
                           @endif
                           @if ((int) $user->active === 1)
                              <form method="POST" action="{{ route('super-admin.users.status', ['id' => $user->id]) }}" class="d-inline js-user-status-form" data-action-label="deactivate" data-user-label="{{ $user->name ?? $user->erp_id ?? $user->email ?? 'this user' }}">
                                 @csrf
                                 <input type="hidden" name="active" value="0">
                                 <button type="submit" class="btn btn-sm btn-danger-100 text-danger-600">Deactivate</button>
                              </form>
                           @else
                              <form method="POST" action="{{ route('super-admin.users.status', ['id' => $user->id]) }}" class="d-inline js-user-status-form" data-action-label="activate" data-user-label="{{ $user->name ?? $user->erp_id ?? $user->email ?? 'this user' }}">
                                 @csrf
                                 <input type="hidden" name="active" value="1">
                                 <button type="submit" class="btn btn-sm btn-success-100 text-success-600">Activate</button>
                              </form>
                           @endif
                        </td>
                     @endif
                  </tr>
               @empty
                  <tr>
                     <td colspan="7" class="text-center py-4">No users found.</td>
                  </tr>
               @endforelse
            </tbody>
         </table>
      </div>
      <div class="p-16">
         {{ $users->links() }}
      </div>
   </div>
</div>
@endsection

@section('footer_scripts')
<script>
   (function () {
      var forms = document.querySelectorAll('.js-user-status-form');
      if (!forms.length) return;

      forms.forEach(function (form) {
         form.addEventListener('submit', function (event) {
            event.preventDefault();

            var actionLabel = form.getAttribute('data-action-label') || 'update';
            var userLabel = form.getAttribute('data-user-label') || 'this user';
            var title = 'Are you sure?';
            var text = 'Do you want to ' + actionLabel + ' ' + userLabel + '?';

            if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
               window.Swal.fire({
                  title: title,
                  text: text,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Yes',
                  cancelButtonText: 'Cancel',
               }).then(function (result) {
                  if (result.isConfirmed) form.submit();
               });
               return;
            }

            if (typeof window.swal === 'function') {
               window.swal({
                  title: title,
                  text: text,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Yes',
                  cancelButtonText: 'Cancel'
               }, function (isConfirm) {
                  if (isConfirm) form.submit();
               });
               return;
            }

            if (window.confirm(text)) {
               form.submit();
            }
         });
      });
   })();
</script>
@endsection
