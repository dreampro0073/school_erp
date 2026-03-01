@extends('layout.layout')

@section('main')
<div ng-controller="studentProfileCtrl" ng-init="init('{{ $studentToken }}');" class="mt-24">
   <div class="d-flex justify-content-between align-items-center mb-16">
      <h5 class="mb-0">Student Profile</h5>
      <div class="d-flex gap-2">
         <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Back to List</a>
         <a ng-if="encId" ng-href="@{{baseUrl + '/admin/students/add/' + encodeURIComponent(encId)}}" class="btn btn-primary">
            <i class="ri-edit-2-line"></i> Edit Student
         </a>
      </div>
   </div>

   <div class="card">
      <div class="card-body">
         <div class="row g-3">
            <div class="col-md-4"><strong>Admission No:</strong> @{{student.admission_no || '-'}}</div>
            <div class="col-md-4"><strong>Name:</strong> @{{student.first_name || student.name || '-'}} @{{student.last_name || ''}}</div>
            <div class="col-md-4"><strong>DOB:</strong> @{{student.dob || '-'}}</div>
            <div class="col-md-4"><strong>Gender:</strong> @{{student.gender || '-'}}</div>
            <div class="col-md-4"><strong>Mobile:</strong> @{{student.mobile || '-'}}</div>
            <div class="col-md-4"><strong>Email:</strong> @{{student.email || '-'}}</div>
            <div class="col-md-12"><strong>Address:</strong> @{{student.address || '-'}}</div>
            <div class="col-md-4"><strong>Aadhar:</strong> @{{student.aadhar_no || '-'}}</div>
            <div class="col-md-4"><strong>Status:</strong> @{{student.active == 0 ? 'Inactive' : 'Active'}}</div>
         </div>

         <hr class="my-4">

         <h6 class="mb-3">Parent Details</h6>
         <div class="row g-3">
            <div class="col-md-4"><strong>Name:</strong> @{{parent.name || parent.parent_name || '-'}}</div>
            <div class="col-md-4"><strong>Mobile:</strong> @{{parent.mobile || parent.phone || '-'}}</div>
            <div class="col-md-4"><strong>Email:</strong> @{{parent.email || '-'}}</div>
            <div class="col-md-12"><strong>Address:</strong> @{{parent.address || '-'}}</div>
            <div class="col-md-4"><strong>Aadhar:</strong> @{{parent.aadhar_no || '-'}}</div>
         </div>
      </div>
   </div>
</div>
@endsection
