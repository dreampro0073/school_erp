<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
	<div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <h6 class="text-lg fw-semibold mb-0">Parent Guardian Detail</h6>
    </div>

	<div class="card-body p-0">
	    <div class="bg-hover-neutral-50 p-20">
	       	<div class="row g-4">
	          	<div class="col-sm-3">
	             	<div class="d-flex align-items-center gap-12">
		              
		                <div class="">
		                   <h6 class="text-md mb-2 fw-medium flex-grow-1">		@{{student.father_name}}
		                   </h6>
		                   <span class="">Father</span>
		                </div>
		            </div>
		        </div>
				<div class="col-sm-3">
					<div class="">
						<h6 class="text-md mb-2 fw-medium flex-grow-1">Phone</h6>
						<span class="">@{{student.father_name}}</span>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="">
						<h6 class="text-md mb-2 fw-medium flex-grow-1">Email</h6>
						<span class="">@{{student.father_mobile}}</span>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="">
						<h6 class="text-md mb-2 fw-medium flex-grow-1">Aadhar No</h6>
						<span class="">@{{student.father_aadhar_no}}</span>
					</div>
				</div>
	       	</div>
	    </div>
	    <div class="bg-hover-neutral-50 p-20">
	       	<div class="row g-4">
				<div class="col-sm-3">
					<div class="d-flex align-items-center gap-12">
					
						<div class="">
							<h6 class="text-md mb-2 fw-medium flex-grow-1">
								@{{student.mother_name}}
							</h6>
							<span class="">Mother</span>
						</div>
					</div>
				</div>
		        <div class="col-sm-3">
		            <div class="">
		                <h6 class="text-md mb-2 fw-medium flex-grow-1">Phone</h6>
		                <span class="">@{{student.mother_mobile}}</span>
		           	</div>
		         </div>
	         	<div class="col-sm-3">
		            <div class="">
		                <h6 class="text-md mb-2 fw-medium flex-grow-1">Email</h6>
		                <span class="">@{{student.mother_email}}</span>
		            </div>
	          	</div>
	          	<div class="col-sm-3">
					<div class="">
						<h6 class="text-md mb-2 fw-medium flex-grow-1">Aadhar No</h6>
						<span class="">@{{student.mother_aadhar_no}}</span>
					</div>
				</div>
	        </div>
	    </div>
	</div>
</div>
<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden mt-20">
	<div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <h6 class="text-lg fw-semibold mb-0">Medical Details</h6>
    </div>

	<div class="card-body p-16">
	    <div class="row">
	    	<div class="col-md-4 mb-10">
	            <h6 class="text-md mb-2 fw-medium flex-grow-1">Blood Group</h6>
	            <span class="">@{{student.weight}}</span>
	        </div>
	        <div class="col-md-4 mb-10">
	            <h6 class="text-md mb-2 fw-medium flex-grow-1">Weight</h6>
	            <span class="">@{{student.weight}}</span>
	        </div>
	        <div class="col-md-4 mb-10">
	            <h6 class="text-md mb-2 fw-medium flex-grow-1">Height</h6>
	            <span class="">@{{student.height}}</span>
	        </div>
	    </div>
	</div>
</div>
<div class="row mt-20">
	<div class="col-md-6">
		<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
			<div
		        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
		        <h6 class="text-lg fw-semibold mb-0">Previous School Details</h6>
		    </div>

			<div class="card-body p-16">
				<div class="mb-10">
		            <h6 class="text-md mb-2 fw-medium flex-grow-1">Shcool Name</h6>
		            <span class="">@{{student.previous_school}}</span>
		        </div>
		        <div class="">
		            <h6 class="text-md mb-2 fw-medium flex-grow-1">Address </h6>
		            <span class="">@{{student.previous_school_address}}</span>
		        </div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
			<div
		        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
		        <h6 class="text-lg fw-semibold mb-0">Address</h6>
		    </div>

			<div class="card-body p-16">
				<div class="mb-10">
		            <h6 class="text-md mb-2 fw-medium flex-grow-1">Residential Address</h6>
		            <span class="">@{{student.residential_address}}</span>
		        </div>
		        <div class="">
		            <h6 class="text-md mb-2 fw-medium flex-grow-1">Permanent Address</h6>
		            <span class="">@{{student.permanent_address}}</span>
		        </div>
			</div>
		</div>
	</div>
</div>
