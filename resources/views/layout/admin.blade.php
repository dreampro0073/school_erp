<li>
   <a href="{{ url('/admin/dashboard') }}">
      <i class="ri-home-4-line"></i>
      <span>Dashboard</span>
   </a>
</li>

<li class="dropdown">
   <a href="javascript:void(0)">
      <i class="ri-user-follow-line"></i>
      <span>Teachers</span>
   </a>
   <ul class="sidebar-submenu">
      <li>
         <a href="{{ url('/admin/teachers') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Teacher List
         </a>
      </li>
      <li>
         <a href="{{ url('/admin/teachers/add') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Add Teacher
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
         <a href="{{ url('/admin/students') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Student List
         </a>
      </li>
      <li>
         <a href="{{ url('/admin/students/add') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Add Student
         </a>
      </li>
   </ul>
</li>

<li>
   <a href="{{ url('/admin/school/index') }}">
      <i class="ri-settings-3-line animate-spin"></i>
      <span>School</span>
   </a>
</li>

@if(false)
<li>
   <a href="{{ route('admin.attendance.index') }}">
      <i class="ri-calendar-check-line"></i>
      <span>Attendance</span>
   </a>
</li>

<li class="dropdown">
   <a href="javascript:void(0)">
      <i class="ri-money-dollar-circle-line"></i>
      <span>Income & Expenses</span>
   </a>
   <ul class="sidebar-submenu">
      <li>
         <a href="{{ route('admin.incomes.index') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Incomes
         </a>
      </li>
      <li>
         <a href="{{ route('admin.income-entries.index') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Income Entries
         </a>
      </li>
      <li>
         <a href="{{ route('admin.expenses.index') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Expenses
         </a>
      </li>
      <li>
         <a href="{{ route('admin.expense-entries.index') }}">
            <i class="ri-circle-fill circle-icon w-auto"></i>
            Expense Entries
         </a>
      </li>
   </ul>
</li>
@endif