
<button type="button" class="sidebar-close-btn">
   <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
</button>
@php
   $authUser = Auth::user();
   $authPriv = $authUser->priv ?? $authUser->privillage ?? $authUser->privilege ?? null;
   $isPrivOne = (int) $authPriv === 1;
@endphp
<div class="">
   <div class="sidebar-logo d-flex align-items-center justify-content-between">
      <a href="index.html" class="">
          <img src="{{url('assets/img/sx1-logo.png')}}" alt="site logo" class="light-logo">
          <img src="{{url('assets/img/sx1-logo-light.png')}}" alt="site logo" class="dark-logo">
          <!-- <img src="assets/images/logo-icon.png" alt="site logo" class="logo-icon"> -->
      </a>
      <button type="button" class="text-xxl d-xl-flex d-none line-height-1 sidebar-toggle text-neutral-500"
         aria-label="Collapse Sidebar">
      <i class="ri-contract-left-line"></i>
      </button>
   </div>
</div>
<!-- User Info start -->
<div class="mx-16 py-12">
   <div class="dropdown profile-dropdown">
      <button type="button"
         class="profile-dropdown__button d-flex align-items-center justify-content-between p-10 w-100 overflow-hidden bg-neutral-50 radius-12 "
         data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
      <span class="d-flex align-items-start gap-10">
      <img src="assets/images/thumbs/leave-request-img2.png" alt="Thumbnail"
         class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
      <span class="profile-dropdown__contents">
      <span class="h6 mb-0 text-md d-block text-primary-light">{{Auth::user()->name}}</span>
      <span class="text-secondary-light text-sm mb-0 d-block">Admin</span>
      </span>
      </span>
      <span class="profile-dropdown__icon pe-8 text-xl d-flex line-height-1">
      <i class="ri-arrow-right-s-line"></i>
      </span>
      </button>
      <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
         @if (!$isPrivOne)
            <li>
               <a href="student-details.html" 
                  class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
               <i class="ri-user-3-line"></i>
               My Profile
               </a>
            </li>
         @endif
         <li>
            <a href="general.html"
               class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
            <i class="ri-settings-3-line"></i>
            Setting
            </a>
         </li>
         <li>
            <a href="javascript:void(0)"
               onclick="document.getElementById('logoutFormSidebar').submit();"
               class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
               <i class="ri-shut-down-line"></i>
               Log Out
            </a>
         </li>
      </ul>
   </div>
</div>
<form id="logoutFormSidebar" method="POST" action="{{ route('logout') }}" class="d-none">
   @csrf
</form>
<!-- User Info end -->
<div class="sidebar-menu-area">
   <ul class="sidebar-menu" id="sidebar-menu">

      <!-- SuperAdmin -->
      @if(Auth::user()->priv == 1) 
         @include("layout.supar_admin")
      @endif

      <!-- Admin -->
      @if(Auth::user()->priv == 2)
         @include("layout.admin")
      @endif      

      <!-- Teacher -->
      @if(Auth::user()->priv == 3)
         @include("layout.teacher")
      @endif      

      <!-- Student -->
      @if(Auth::user()->priv == 4)
         @include("layout.student")
      @endif      

      <!-- Gurdian -->
      @if(Auth::user()->priv == 5)
         @include("layout.parents")
      @endif

      @if ($isPrivOne)
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-home-4-line"></i>
         <span>Dashboard</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="{{ url('/super-admin/dashboard') }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Overall
               </a>
            </li>
            <li>
               <a href="{{ route('super-admin.users.type', ['type' => 'schools']) }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Schools
               </a>
            </li>
            <li>
               <a href="{{ route('super-admin.users.type', ['type' => 'students']) }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Students
               </a>
            </li>
            <li>
               <a href="{{ route('super-admin.users.type', ['type' => 'teachers']) }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Teachers
               </a>
            </li>
            <li>
               <a href="{{ route('super-admin.users.type', ['type' => 'parents']) }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Parents
               </a>
            </li>
         </ul>
      </li>
      <li>
         <a href="{{ route('super-admin.services.index') }}">
         <i class="ri-service-line"></i>
         <span>Services</span>
         </a>
      </li>
      <li>
         <a href="{{ route('super-admin.standards.index') }}">
         <i class="ri-stack-line"></i>
         <span>Standards</span>
         </a>
      </li>
      <li>
         <a href="{{ route('super-admin.sections.index') }}">
         <i class="ri-layout-grid-line"></i>
         <span>Sections</span>
         </a>
      </li>
      <li>
         <a href="{{ route('super-admin.subjects.index') }}">
         <i class="ri-book-open-line"></i>
         <span>Subjects</span>
         </a>
      </li>
      <li>
         <a href="{{ route('super-admin.fee-types.index') }}">
         <i class="ri-money-dollar-circle-line"></i>
         <span>Fee Types</span>
         </a>
      </li>
      <li>
         <a href="general.html">
         <i class="ri-settings-3-line"></i>
         <span>Setting</span>
         </a>
      </li>
      <li>
         <a href="{{ route('worklog.index') }}">
         <i class="ri-file-list-3-line"></i>
         <span>Worklog</span>
         </a>
      </li>
      <li>
         <a href="javascript:void(0)" onclick="document.getElementById('logoutFormSidebar').submit();">
         <i class="ri-shut-down-line"></i>
         <span>Logout</span>
         </a>
      </li>
      @else
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-home-4-line"></i>
         <span>Dashboard </span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="{{ url('/admin/dashboard') }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Overview
               </a>
            </li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-graduation-cap-line"></i>
         <span>Students</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="{{ route('admin.students.index') }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Student List
               </a>
            </li>
            <li>
               <a href="{{ route('admin.students.add') }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Add Student
               </a>
            </li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-user-follow-line"></i>
         <span>Teachers</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="{{ route('admin.teachers.index') }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Teacher List
               </a>
            </li>
            <li>
               <a href="{{ route('admin.teachers.add') }}">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Add Teacher
               </a>
            </li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-list-view"></i>
         <span>Classes</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="section-list.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Section
               </a>
            </li>
            <li>
               <a href="subject-list.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Subjects
               </a>
            </li>
            <li>
               <a href="class-list.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Class List
               </a>
            </li>
            <li>
               <a href="class-room-list.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Class Room
               </a>
            </li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-file-edit-line"></i>
         <span>Examinations</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="exam.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Exam
               </a>
            </li>
            <li>
               <a href="exam-schedule.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Exam Schedule
               </a>
            </li>
            <li>
               <a href="exam-result.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Exam Result
               </a>
            </li>
         </ul>
      </li>
      <li>
         <a href="{{ route('worklog.index') }}">
         <i class="ri-file-list-3-line"></i>
         <span>Worklog</span>
         </a>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-money-dollar-circle-line"></i>
         <span>Fees Collection</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="fees-collect.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Fees Collect
               </a>
            </li>
            <li>
               <a href="fees-type.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Fees Type
               </a>
            </li>
            <li>
               <a href="fees-group.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Fees Group
               </a>
            </li>
            <li>
               <a href="fees-discount.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Fees Discount
               </a>
            </li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-calendar-check-line"></i>
         <span>Attendance</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="student-attendance.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Student Attendance
               </a>
            </li>
            <li>
               <a href="teacher-attendance.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Teacher Attendance
               </a>
            </li>
            <li>
               <a href="employee-attendance.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Employee Attendance
               </a>
            </li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-time-line"></i>
         <span>Leaves</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="leave-types.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Leave Types
               </a>
            </li>
            <li>
               <a href="leave-request.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Leave Request
               </a>
            </li>
         </ul>
      </li>
      <li>
         <a href="certificate.html">
         <i class="ri-home-4-line"></i>
         <span>Certificate </span>
         </a>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-book-2-line"></i>
         <span>Library</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="books-list.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Books List
               </a>
            </li>
            <li>
               <a href="members-list.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Members List
               </a>
            </li>
            <li>
               <a href="member-details.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Members Details
               </a>
            </li>
            <li>
               <a href="issue-return.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Issue Return
               </a>
            </li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-money-dollar-circle-line"></i>
         <span>Income & Expenses</span>
         </a>
         <ul class="sidebar-submenu">
            <li><a href="{{ route('admin.incomes.index') }}"><i class="ri-circle-fill circle-icon w-auto"></i>Incomes</a></li>
            <li><a href="{{ route('admin.income-entries.index') }}"><i class="ri-circle-fill circle-icon w-auto"></i>Income Entries</a></li>
            <li><a href="{{ route('admin.expenses.index') }}"><i class="ri-circle-fill circle-icon w-auto"></i>Expenses</a></li>
            <li><a href="{{ route('admin.expense-entries.index') }}"><i class="ri-circle-fill circle-icon w-auto"></i>Expense Entries</a></li>
         </ul>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-user-settings-line"></i>
         <span>HRM</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="employee-list.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Employee List
               </a>
            </li>
            <li>
               <a href="employee-details.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Employee Details
               </a>
            </li>
            <li>
               <a href="add-new-employee.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Add New Employee
               </a>
            </li>
            <li>
               <a href="payroll.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Payroll
               </a>
            </li>
            <li>
               <a href="designation.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Designation
               </a>
            <li>
               <a href="department.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Department
               </a>
            </li>
         </ul>
      </li>
      <li>
         <a href="notice-board.html">
         <i class="ri-booklet-line"></i>
         <span>Notice Board </span>
         </a>
      </li>
      <li>
         <a href="event.html">
         <i class="ri-calendar-event-line"></i>
         <span>Event </span>
         </a>
      </li>
      <li>
         <a href="message.html">
         <i class="ri-message-2-line"></i>
         <span>Message </span>
         </a>
      </li>
      <li>
         <a href="subscription-plan.html">
         <i class="ri-price-tag-3-line"></i>
         <span>Subscription Plan </span>
         </a>
      </li>
      <li>
         <a href="role-access.html">
         <i class="ri-macbook-line"></i>
         <span>Role & Access</span>
         </a>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-shield-check-line"></i>
         <span>Authentication</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="login.html"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Login</a>
            </li>
            <li>
               <a href="register.html"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Register</a>
            </li>
         </ul>
      </li>
      <li>
         <a href="assign-role-plan.html">
         <i class="ri-user-follow-line"></i>
         <span>Assign Role</span>
         </a>
      </li>
      <li class="dropdown">
         <a href="javascript:void(0)">
         <i class="ri-user-settings-line"></i>
         <span>Settings</span>
         </a>
         <ul class="sidebar-submenu">
            <li>
               <a href="general.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               General
               </a>
            </li>
            <li>
               <a href="notification.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Notification
               </a>
            </li>
            <li>
               <a href="currencies.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Currencies
               </a>
            </li>
            <li>
               <a href="languages.html">
               <i class="ri-circle-fill circle-icon w-auto"></i>
               Languages
               </a>
            </li>
         </ul>
      </li>
      @endif
   </ul>
</div>
