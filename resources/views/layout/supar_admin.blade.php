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
