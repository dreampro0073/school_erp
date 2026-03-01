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
   <a href="role-access.html">
   <i class="ri-macbook-line"></i>
   <span>Role & Access</span>
   </a>
</li>
