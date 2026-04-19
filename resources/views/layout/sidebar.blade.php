
<button type="button" class="sidebar-close-btn">
   <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
</button>
<?php 

   $school = App\Models\User::schoolDetails();
?>
@php
   $authUser = Auth::user();
   $authPriv = (int) ($authUser->priv ?? $authUser->privillage ?? $authUser->privilege ?? 0);
   $roleLabelMap = [
      1 => 'Super Admin',
      2 => 'Admin',
      3 => 'Teacher',
      4 => 'Student',
      5 => 'Guardian',
      6 => 'Aspirant',
   ];
   $roleLabel = $roleLabelMap[$authPriv] ?? 'User';

   $dashboardLinks = [
      1 => url('/super-admin/dashboard'),
      2 => url('/admin/dashboard'),
      3 => url('/teachers/dashboard'),
      4 => '#',
      5 => url('/gurdian/dashboard'),
      6 => url('/aspirant/dashboard'),
   ];

   $dashboardUrl = $dashboardLinks[$authPriv] ?? url('/');
@endphp

<div class="">
   <div class="sidebar-logo d-flex align-items-center justify-content-between">
      <a href="{{ $dashboardUrl }}">
         <img src="{{ url('assets/img/sx1-logo.png') }}" alt="site logo" class="light-logo">
         <img src="{{ url('assets/img/sx1-logo-light.png') }}" alt="site logo" class="dark-logo">
      </a>
      <button type="button" class="text-xxl d-xl-flex d-none line-height-1 sidebar-toggle text-neutral-500"
         aria-label="Collapse Sidebar">
         <i class="ri-contract-left-line"></i>
      </button>
   </div>
</div>

<div class="mx-16 py-12">
   <div class="dropdown profile-dropdown">
      <button type="button"
         class="profile-dropdown__button d-flex align-items-center justify-content-between p-10 w-100 overflow-hidden bg-neutral-50 radius-12"
         data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
         <span class="d-flex align-items-center gap-10">

            @if(Auth::user()->priv != 1 && Auth::user()->priv != 6)
            <img src="{{$school->logo }}" alt="Thumbnail"
               class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
               <span class="text-secondary-light text-sm mb-0 d-block">{{ $roleLabel }}</span>
            </span>
            @else
            <span class="profile-dropdown__contents">
               <span class="h6 mb-0 text-md d-block text-primary-light">{{ $authUser->name }}</span>
               <span class="text-secondary-light text-sm mb-0 d-block">{{ $roleLabel }}</span>
            </span>
            @endif
         </span>
         <span class="profile-dropdown__icon pe-8 text-xl d-flex line-height-1">
            <i class="ri-arrow-right-s-line"></i>
         </span>
      </button>
      <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
         <li>
            <a href="javascript:void(0);"
               onclick="document.getElementById('logoutFormSidebar').submit();"
               class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
               <i class="ri-shut-down-line"></i>
               Log Out
            </a>
         </li>
      </ul>
   </div>
</div>

<div class="sidebar-menu-area">
   <ul class="sidebar-menu" id="sidebar-menu">
      @if ($authPriv === 1)
         @include('layout.supar_admin')
      @elseif ($authPriv === 2)
         @include('layout.admin')
      @elseif ($authPriv === 3)
         @include('layout.teacher')
      @elseif ($authPriv === 4)
         @include('layout.student')
      @elseif ($authPriv === 5)
         @include('layout.parents')
      @elseif ($authPriv === 6)
         @include('layout.aspirants')
      @endif
      
      <li>
         <a href="{{ url('chat') }}">
            <i class="ri-message-2-line"></i>
            <span>Chat</span>
         </a>
      </li>

      <li>
         <a href="{{ url('worklog') }}">
            <i class="ri-file-list-3-line"></i>
            <span>Worklog</span>
         </a>
      </li>      

      <li>
         <a href="{{ url('settings') }}">
            <i class="ri-settings-3-line"></i>
            <span>Settings</span>
         </a>
      </li>

      <li class="nav-item">
         <a href="{{url('/logout')}}" onclick="return confirm('Are you sure to Logout?');" >
           <i class="ri-shut-down-line"></i>
           <span class="title">Logout</span>
         </a>
     </li>
   </ul>
</div>
