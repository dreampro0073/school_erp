@extends('admin.layout')

@section('main')
<div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
	<div class="">
	    <h6 class="fw-semibold mb-0">Dashboard</h6>
		    <p class="text-neutral-600 mt-4 mb-0">School -&gt; Manage your school, track attendance, expense, and net worth.</p>
		  </div>
		</div>
	</div>
<div ng-controller="dashboardCtrl" ng-init="init();">
	<div class="row gy-4">
   <div class="col-xxl-8">
      <div class="row gy-4">
         <div class="col-xxl-4 col-sm-6">
            <div class="card shadow-1 radius-8 gradient-bg-end-1 h-100">
               <div class="card-body p-20">
                  <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                     <div class="w-44-px h-44-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                        <img src="assets/images/icons/dashboard-icon1.png" alt="Icon">
                     </div>
                     <p class="fw-medium text-primary-light mb-1">Total Student</p>
                  </div>
                  <h6 class="mb-0">20,000</h6>
                  <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                     <span class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                        10%
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                     </span>
                     +5 This Month
                  </p>
               </div>
            </div>
         </div>
         <div class="col-xxl-4 col-sm-6">
            <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
               <div class="card-body p-20">
                  <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                     <div class="w-44-px h-44-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                        <img src="assets/images/icons/dashboard-icon2.png" alt="Icon">
                     </div>
                     <p class="fw-medium text-primary-light mb-1">Total Student</p>
                  </div>
                  <h6 class="mb-0">20,000</h6>
                  <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                     <span class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                        10%
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                     </span>
                     +5 This Month
                  </p>
               </div>
            </div>
         </div>
         <div class="col-xxl-4 col-sm-6">
            <div class="card shadow-1 radius-8 gradient-bg-end-3 h-100">
               <div class="card-body p-20">
                  <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                     <div class="w-44-px h-44-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                        <img src="assets/images/icons/dashboard-icon3.png" alt="Icon">
                     </div>
                     <p class="fw-medium text-primary-light mb-1">Total Student</p>
                  </div>
                  <h6 class="mb-0">20,000</h6>
                  <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                     <span class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                        10%
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                     </span>
                     +5 This Month
                  </p>
               </div>
            </div>
         </div>
         <div class="col-xxl-4 col-sm-6">
            <div class="card shadow-1 radius-8 gradient-bg-end-4 h-100">
               <div class="card-body p-20">
                  <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                     <div class="w-44-px h-44-px bg-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                        <img src="assets/images/icons/dashboard-icon4.png" alt="Icon">
                     </div>
                     <p class="fw-medium text-primary-light mb-1">Total Student</p>
                  </div>
                  <h6 class="mb-0">20,000</h6>
                  <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                     <span class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                        10%
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                     </span>
                     +5 This Month
                  </p>
               </div>
            </div>
         </div>
         <div class="col-xxl-4 col-sm-6">
            <div class="card shadow-1 radius-8 gradient-bg-end-5 h-100">
               <div class="card-body p-20">
                  <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                     <div class="w-44-px h-44-px bg-success-600 rounded-circle d-flex justify-content-center align-items-center">
                        <img src="assets/images/icons/dashboard-icon5.png" alt="Icon">
                     </div>
                     <p class="fw-medium text-primary-light mb-1">Total Student</p>
                  </div>
                  <h6 class="mb-0">20,000</h6>
                  <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                     <span class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                        10%
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                     </span>
                     +5 This Month
                  </p>
               </div>
            </div>
         </div>
         <div class="col-xxl-4 col-sm-6">
            <div class="card shadow-1 radius-8 gradient-bg-end-6 h-100">
               <div class="card-body p-20">
                  <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                     <div class="w-44-px h-44-px bg-cyan-600 rounded-circle d-flex justify-content-center align-items-center">
                        <img src="assets/images/icons/dashboard-icon6.png" alt="Icon">
                     </div>
                     <p class="fw-medium text-primary-light mb-1">Total Student</p>
                  </div>
                  <h6 class="mb-0">20,000</h6>
                  <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                     <span class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                        10%
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                     </span>
                     +5 This Month
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xxl-4">
      <div class="card h-100">
         <div class="card-body p-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
               <h6 class="text-lg mb-0">Student Attendance</h6>
            </div>
            <div class="p-20">
               <div class="d-flex gap-6">
                  <div class="h-44-px bg-primary-600 rounded" style="width: 87%;"></div>
                  <div class="h-44-px bg-warning-600 rounded" style="width: 40%;"></div>
                  <div class="h-44-px bg-purple-600 rounded" style="width: 20%;"></div>
                  <div class="h-44-px bg-success-600 rounded" style="width: 20%;"></div>
               </div>
               <div class="mt-32 d-flex flex-column gap-24">
                  <div class="d-flex align-items-center justify-content-between">
                     <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                        <span class="text-neutral-600">Present </span>
                     </div>
                     <span class="fw-semibold text-primary-light">87%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                     <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-warning-600"></span>
                        <span class="text-neutral-600">Absent: </span>
                     </div>
                     <span class="fw-semibold text-primary-light">40%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                     <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-purple-600"></span>
                        <span class="text-neutral-600">Late </span>
                     </div>
                     <span class="fw-semibold text-primary-light">20%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                     <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-success-600"></span>
                        <span class="text-neutral-600">Half day </span>
                     </div>
                     <span class="fw-semibold text-primary-light">20%</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-12">
      <div class="row gy-4">
         <div class="col-xxl-8">
            <div class="row gy-4">
               <div class="col-12">
                  <div class="card h-100">
                     <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                           <h6 class="text-lg mb-0">Revenue Statistic</h6>
                        </div>
                        <div class="p-20">
                           <ul class="d-flex flex-wrap align-items-center justify-content-center mb-16 gap-3">
                              <li class="d-flex align-items-center gap-8">
                                 <span class="w-12-px h-12-px radius-2 rotate-45-deg bg-primary-600"></span>
                                 <span class="text-secondary-light text-sm fw-semibold">
                                 Total Fee:
                                 <span class="text-primary-light fw-bold">$500</span>
                                 </span>
                              </li>
                              <li class="d-flex align-items-center gap-8">
                                 <span class="w-12-px h-12-px radius-2 rotate-45-deg bg-warning-600"></span>
                                 <span class="text-secondary-light text-sm font-semibold">
                                 Collected Fee:
                                 <span class="text-primary-light fw-bold"> $300</span>
                                 </span>
                              </li>
                           </ul>
                           <div id="revenueStatistic" style="min-height: 265px;">
                              <div id="apexchartsj3nzualp" class="apexcharts-canvas apexchartsj3nzualp apexcharts-theme-light" style="width: 687px; height: 250px;">
                                 <svg id="SvgjsSvg2423" width="687" height="250" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                                    <foreignObject x="0" y="0" width="687" height="250">
                                       <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 125px;"></div>
                                    </foreignObject>
                                    <g id="SvgjsG2652" class="apexcharts-yaxis" rel="0" transform="translate(35.15625, 0)">
                                       <g id="SvgjsG2653" class="apexcharts-yaxis-texts-g">
                                          <text id="SvgjsText2655" font-family="Helvetica, Arial, sans-serif" x="20" y="31.5" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                             <tspan id="SvgjsTspan2656">$100k</tspan>
                                             <title>$100k</title>
                                          </text>
                                          <text id="SvgjsText2658" font-family="Helvetica, Arial, sans-serif" x="20" y="67.9696" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                             <tspan id="SvgjsTspan2659">$80k</tspan>
                                             <title>$80k</title>
                                          </text>
                                          <text id="SvgjsText2661" font-family="Helvetica, Arial, sans-serif" x="20" y="104.4392" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                             <tspan id="SvgjsTspan2662">$60k</tspan>
                                             <title>$60k</title>
                                          </text>
                                          <text id="SvgjsText2664" font-family="Helvetica, Arial, sans-serif" x="20" y="140.90879999999999" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                             <tspan id="SvgjsTspan2665">$40k</tspan>
                                             <title>$40k</title>
                                          </text>
                                          <text id="SvgjsText2667" font-family="Helvetica, Arial, sans-serif" x="20" y="177.3784" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                             <tspan id="SvgjsTspan2668">$20k</tspan>
                                             <title>$20k</title>
                                          </text>
                                          <text id="SvgjsText2670" font-family="Helvetica, Arial, sans-serif" x="20" y="213.848" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                             <tspan id="SvgjsTspan2671">$0k</tspan>
                                             <title>$0k</title>
                                          </text>
                                       </g>
                                    </g>
                                    <g id="SvgjsG2425" class="apexcharts-inner apexcharts-graphical" transform="translate(65.15625, 30)">
                                       <defs id="SvgjsDefs2424">
                                          <linearGradient id="SvgjsLinearGradient2428" x1="0" y1="0" x2="0" y2="1">
                                             <stop id="SvgjsStop2429" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                             <stop id="SvgjsStop2430" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                             <stop id="SvgjsStop2431" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                          </linearGradient>
                                          <clipPath id="gridRectMaskj3nzualp">
                                             <rect id="SvgjsRect2433" width="615.84375" height="182.348" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                          </clipPath>
                                          <clipPath id="forecastMaskj3nzualp"></clipPath>
                                          <clipPath id="nonForecastMaskj3nzualp"></clipPath>
                                          <clipPath id="gridRectMarkerMaskj3nzualp">
                                             <rect id="SvgjsRect2434" width="615.84375" height="186.348" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                          </clipPath>
                                       </defs>
                                       <rect id="SvgjsRect2432" width="25.49348958333333" height="182.348" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient2428)" class="apexcharts-xcrosshairs" y2="182.348" filter="none" fill-opacity="0.9"></rect>
                                       <line id="SvgjsLine2590" x1="0" y1="183.348" x2="0" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2591" x1="50.986979166666664" y1="183.348" x2="50.986979166666664" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2592" x1="101.97395833333333" y1="183.348" x2="101.97395833333333" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2593" x1="152.9609375" y1="183.348" x2="152.9609375" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2594" x1="203.94791666666666" y1="183.348" x2="203.94791666666666" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2595" x1="254.93489583333331" y1="183.348" x2="254.93489583333331" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2596" x1="305.921875" y1="183.348" x2="305.921875" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2597" x1="356.9088541666667" y1="183.348" x2="356.9088541666667" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2598" x1="407.89583333333337" y1="183.348" x2="407.89583333333337" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2599" x1="458.88281250000006" y1="183.348" x2="458.88281250000006" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2600" x1="509.86979166666674" y1="183.348" x2="509.86979166666674" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2601" x1="560.8567708333334" y1="183.348" x2="560.8567708333334" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <line id="SvgjsLine2602" x1="611.84375" y1="183.348" x2="611.84375" y2="189.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                       <g id="SvgjsG2586" class="apexcharts-grid">
                                          <g id="SvgjsG2587" class="apexcharts-gridlines-horizontal">
                                             <line id="SvgjsLine2604" x1="0" y1="36.4696" x2="611.84375" y2="36.4696" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                             <line id="SvgjsLine2605" x1="0" y1="72.9392" x2="611.84375" y2="72.9392" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                             <line id="SvgjsLine2606" x1="0" y1="109.4088" x2="611.84375" y2="109.4088" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                             <line id="SvgjsLine2607" x1="0" y1="145.8784" x2="611.84375" y2="145.8784" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                          </g>
                                          <g id="SvgjsG2588" class="apexcharts-gridlines-vertical"></g>
                                          <line id="SvgjsLine2610" x1="0" y1="182.348" x2="611.84375" y2="182.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                          <line id="SvgjsLine2609" x1="0" y1="1" x2="0" y2="182.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                       </g>
                                       <g id="SvgjsG2435" class="apexcharts-bar-series apexcharts-plot-series">
                                          <g id="SvgjsG2436" class="apexcharts-series" seriesName="TotalxFee" rel="1" data:realIndex="0">
                                             <path id="SvgjsPath2440" d="M 12.746744791666668 182.34900000000002 L 12.746744791666668 136.76200000000003 L 38.240234375 136.76200000000003 L 38.240234375 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 12.746744791666668 182.34900000000002 L 12.746744791666668 136.76200000000003 L 38.240234375 136.76200000000003 L 38.240234375 182.34900000000002 z" pathFrom="M 12.746744791666668 182.34900000000002 L 12.746744791666668 182.34900000000002 L 38.240234375 182.34900000000002 L 38.240234375 182.34900000000002 L 38.240234375 182.34900000000002 L 38.240234375 182.34900000000002 L 38.240234375 182.34900000000002 L 12.746744791666668 182.34900000000002 z" cy="136.76100000000002" cx="63.73372395833333" j="0" val="25" barHeight="45.587" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2446" d="M 63.73372395833333 182.34900000000002 L 63.73372395833333 118.52720000000002 L 89.22721354166666 118.52720000000002 L 89.22721354166666 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 63.73372395833333 182.34900000000002 L 63.73372395833333 118.52720000000002 L 89.22721354166666 118.52720000000002 L 89.22721354166666 182.34900000000002 z" pathFrom="M 63.73372395833333 182.34900000000002 L 63.73372395833333 182.34900000000002 L 89.22721354166666 182.34900000000002 L 89.22721354166666 182.34900000000002 L 89.22721354166666 182.34900000000002 L 89.22721354166666 182.34900000000002 L 89.22721354166666 182.34900000000002 L 63.73372395833333 182.34900000000002 z" cy="118.52620000000002" cx="114.720703125" j="1" val="35" barHeight="63.8218" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2452" d="M 114.720703125 182.34900000000002 L 114.720703125 91.17500000000001 L 140.21419270833331 91.17500000000001 L 140.21419270833331 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 114.720703125 182.34900000000002 L 114.720703125 91.17500000000001 L 140.21419270833331 91.17500000000001 L 140.21419270833331 182.34900000000002 z" pathFrom="M 114.720703125 182.34900000000002 L 114.720703125 182.34900000000002 L 140.21419270833331 182.34900000000002 L 140.21419270833331 182.34900000000002 L 140.21419270833331 182.34900000000002 L 140.21419270833331 182.34900000000002 L 140.21419270833331 182.34900000000002 L 114.720703125 182.34900000000002 z" cy="91.174" cx="165.70768229166666" j="2" val="50" barHeight="91.174" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2458" d="M 165.70768229166666 182.34900000000002 L 165.70768229166666 72.94020000000002 L 191.201171875 72.94020000000002 L 191.201171875 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 165.70768229166666 182.34900000000002 L 165.70768229166666 72.94020000000002 L 191.201171875 72.94020000000002 L 191.201171875 182.34900000000002 z" pathFrom="M 165.70768229166666 182.34900000000002 L 165.70768229166666 182.34900000000002 L 191.201171875 182.34900000000002 L 191.201171875 182.34900000000002 L 191.201171875 182.34900000000002 L 191.201171875 182.34900000000002 L 191.201171875 182.34900000000002 L 165.70768229166666 182.34900000000002 z" cy="72.93920000000001" cx="216.69466145833331" j="3" val="60" barHeight="109.4088" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2464" d="M 216.69466145833331 182.34900000000002 L 216.69466145833331 134.93852 L 242.18815104166663 134.93852 L 242.18815104166663 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 216.69466145833331 182.34900000000002 L 216.69466145833331 134.93852 L 242.18815104166663 134.93852 L 242.18815104166663 182.34900000000002 z" pathFrom="M 216.69466145833331 182.34900000000002 L 216.69466145833331 182.34900000000002 L 242.18815104166663 182.34900000000002 L 242.18815104166663 182.34900000000002 L 242.18815104166663 182.34900000000002 L 242.18815104166663 182.34900000000002 L 242.18815104166663 182.34900000000002 L 216.69466145833331 182.34900000000002 z" cy="134.93752" cx="267.681640625" j="4" val="26" barHeight="47.41048" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2470" d="M 267.681640625 182.34900000000002 L 267.681640625 145.8794 L 293.1751302083333 145.8794 L 293.1751302083333 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 267.681640625 182.34900000000002 L 267.681640625 145.8794 L 293.1751302083333 145.8794 L 293.1751302083333 182.34900000000002 z" pathFrom="M 267.681640625 182.34900000000002 L 267.681640625 182.34900000000002 L 293.1751302083333 182.34900000000002 L 293.1751302083333 182.34900000000002 L 293.1751302083333 182.34900000000002 L 293.1751302083333 182.34900000000002 L 293.1751302083333 182.34900000000002 L 267.681640625 182.34900000000002 z" cy="145.8784" cx="318.6686197916667" j="5" val="20" barHeight="36.4696" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2476" d="M 318.6686197916667 182.34900000000002 L 318.6686197916667 109.40980000000002 L 344.162109375 109.40980000000002 L 344.162109375 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 318.6686197916667 182.34900000000002 L 318.6686197916667 109.40980000000002 L 344.162109375 109.40980000000002 L 344.162109375 182.34900000000002 z" pathFrom="M 318.6686197916667 182.34900000000002 L 318.6686197916667 182.34900000000002 L 344.162109375 182.34900000000002 L 344.162109375 182.34900000000002 L 344.162109375 182.34900000000002 L 344.162109375 182.34900000000002 L 344.162109375 182.34900000000002 L 318.6686197916667 182.34900000000002 z" cy="109.40880000000001" cx="369.65559895833337" j="6" val="40" barHeight="72.9392" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2482" d="M 369.65559895833337 182.34900000000002 L 369.65559895833337 145.8794 L 395.1490885416667 145.8794 L 395.1490885416667 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 369.65559895833337 182.34900000000002 L 369.65559895833337 145.8794 L 395.1490885416667 145.8794 L 395.1490885416667 182.34900000000002 z" pathFrom="M 369.65559895833337 182.34900000000002 L 369.65559895833337 182.34900000000002 L 395.1490885416667 182.34900000000002 L 395.1490885416667 182.34900000000002 L 395.1490885416667 182.34900000000002 L 395.1490885416667 182.34900000000002 L 395.1490885416667 182.34900000000002 L 369.65559895833337 182.34900000000002 z" cy="145.8784" cx="420.64257812500006" j="7" val="20" barHeight="36.4696" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2488" d="M 420.64257812500006 182.34900000000002 L 420.64257812500006 91.17500000000001 L 446.13606770833337 91.17500000000001 L 446.13606770833337 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 420.64257812500006 182.34900000000002 L 420.64257812500006 91.17500000000001 L 446.13606770833337 91.17500000000001 L 446.13606770833337 182.34900000000002 z" pathFrom="M 420.64257812500006 182.34900000000002 L 420.64257812500006 182.34900000000002 L 446.13606770833337 182.34900000000002 L 446.13606770833337 182.34900000000002 L 446.13606770833337 182.34900000000002 L 446.13606770833337 182.34900000000002 L 446.13606770833337 182.34900000000002 L 420.64257812500006 182.34900000000002 z" cy="91.174" cx="471.62955729166674" j="8" val="50" barHeight="91.174" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2494" d="M 471.62955729166674 182.34900000000002 L 471.62955729166674 153.17332000000002 L 497.12304687500006 153.17332000000002 L 497.12304687500006 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 471.62955729166674 182.34900000000002 L 471.62955729166674 153.17332000000002 L 497.12304687500006 153.17332000000002 L 497.12304687500006 182.34900000000002 z" pathFrom="M 471.62955729166674 182.34900000000002 L 471.62955729166674 182.34900000000002 L 497.12304687500006 182.34900000000002 L 497.12304687500006 182.34900000000002 L 497.12304687500006 182.34900000000002 L 497.12304687500006 182.34900000000002 L 497.12304687500006 182.34900000000002 L 471.62955729166674 182.34900000000002 z" cy="153.17232" cx="522.6165364583334" j="9" val="16" barHeight="29.17568" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2500" d="M 522.6165364583334 182.34900000000002 L 522.6165364583334 164.1142 L 548.1100260416667 164.1142 L 548.1100260416667 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 522.6165364583334 182.34900000000002 L 522.6165364583334 164.1142 L 548.1100260416667 164.1142 L 548.1100260416667 182.34900000000002 z" pathFrom="M 522.6165364583334 182.34900000000002 L 522.6165364583334 182.34900000000002 L 548.1100260416667 182.34900000000002 L 548.1100260416667 182.34900000000002 L 548.1100260416667 182.34900000000002 L 548.1100260416667 182.34900000000002 L 548.1100260416667 182.34900000000002 L 522.6165364583334 182.34900000000002 z" cy="164.1132" cx="573.603515625" j="10" val="10" barHeight="18.2348" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2506" d="M 573.603515625 182.34900000000002 L 573.603515625 109.40980000000002 L 599.0970052083334 109.40980000000002 L 599.0970052083334 182.34900000000002 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 573.603515625 182.34900000000002 L 573.603515625 109.40980000000002 L 599.0970052083334 109.40980000000002 L 599.0970052083334 182.34900000000002 z" pathFrom="M 573.603515625 182.34900000000002 L 573.603515625 182.34900000000002 L 599.0970052083334 182.34900000000002 L 599.0970052083334 182.34900000000002 L 599.0970052083334 182.34900000000002 L 599.0970052083334 182.34900000000002 L 599.0970052083334 182.34900000000002 L 573.603515625 182.34900000000002 z" cy="109.40880000000001" cx="624.5904947916666" j="11" val="40" barHeight="72.9392" barWidth="25.49348958333333"></path>
                                             <g id="SvgjsG2438" class="apexcharts-bar-goals-markers" style="pointer-events: none">
                                                <g id="SvgjsG2439" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2445" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2451" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2457" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2463" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2469" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2475" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2481" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2487" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2493" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2499" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2505" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                             </g>
                                          </g>
                                          <g id="SvgjsG2511" class="apexcharts-series" seriesName="CollectedxFee" rel="2" data:realIndex="1">
                                             <path id="SvgjsPath2515" d="M 12.746744791666668 136.76300000000003 L 12.746744791666668 109.41080000000004 L 38.240234375 109.41080000000004 L 38.240234375 136.76300000000003 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 12.746744791666668 136.76300000000003 L 12.746744791666668 109.41080000000004 L 38.240234375 109.41080000000004 L 38.240234375 136.76300000000003 z" pathFrom="M 12.746744791666668 136.76300000000003 L 12.746744791666668 136.76300000000003 L 38.240234375 136.76300000000003 L 38.240234375 136.76300000000003 L 38.240234375 136.76300000000003 L 38.240234375 136.76300000000003 L 38.240234375 136.76300000000003 L 12.746744791666668 136.76300000000003 z" cy="109.40980000000003" cx="63.73372395833333" j="0" val="15" barHeight="27.3522" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2521" d="M 63.73372395833333 118.52820000000003 L 63.73372395833333 89.35252000000003 L 89.22721354166666 89.35252000000003 L 89.22721354166666 118.52820000000003 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 63.73372395833333 118.52820000000003 L 63.73372395833333 89.35252000000003 L 89.22721354166666 89.35252000000003 L 89.22721354166666 118.52820000000003 z" pathFrom="M 63.73372395833333 118.52820000000003 L 63.73372395833333 118.52820000000003 L 89.22721354166666 118.52820000000003 L 89.22721354166666 118.52820000000003 L 89.22721354166666 118.52820000000003 L 89.22721354166666 118.52820000000003 L 89.22721354166666 118.52820000000003 L 63.73372395833333 118.52820000000003 z" cy="89.35152000000002" cx="114.720703125" j="1" val="16" barHeight="29.17568" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2527" d="M 114.720703125 91.17600000000002 L 114.720703125 47.41248000000001 L 140.21419270833331 47.41248000000001 L 140.21419270833331 91.17600000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 114.720703125 91.17600000000002 L 114.720703125 47.41248000000001 L 140.21419270833331 47.41248000000001 L 140.21419270833331 91.17600000000002 z" pathFrom="M 114.720703125 91.17600000000002 L 114.720703125 91.17600000000002 L 140.21419270833331 91.17600000000002 L 140.21419270833331 91.17600000000002 L 140.21419270833331 91.17600000000002 L 140.21419270833331 91.17600000000002 L 140.21419270833331 91.17600000000002 L 114.720703125 91.17600000000002 z" cy="47.41148000000001" cx="165.70768229166666" j="2" val="24" barHeight="43.76352" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2533" d="M 165.70768229166666 72.94120000000002 L 165.70768229166666 18.23680000000002 L 191.201171875 18.23680000000002 L 191.201171875 72.94120000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 165.70768229166666 72.94120000000002 L 165.70768229166666 18.23680000000002 L 191.201171875 18.23680000000002 L 191.201171875 72.94120000000002 z" pathFrom="M 165.70768229166666 72.94120000000002 L 165.70768229166666 72.94120000000002 L 191.201171875 72.94120000000002 L 191.201171875 72.94120000000002 L 191.201171875 72.94120000000002 L 191.201171875 72.94120000000002 L 191.201171875 72.94120000000002 L 165.70768229166666 72.94120000000002 z" cy="18.23580000000002" cx="216.69466145833331" j="3" val="30" barHeight="54.7044" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2539" d="M 216.69466145833331 134.93952000000002 L 216.69466145833331 98.46992000000002 L 242.18815104166663 98.46992000000002 L 242.18815104166663 134.93952000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 216.69466145833331 134.93952000000002 L 216.69466145833331 98.46992000000002 L 242.18815104166663 98.46992000000002 L 242.18815104166663 134.93952000000002 z" pathFrom="M 216.69466145833331 134.93952000000002 L 216.69466145833331 134.93952000000002 L 242.18815104166663 134.93952000000002 L 242.18815104166663 134.93952000000002 L 242.18815104166663 134.93952000000002 L 242.18815104166663 134.93952000000002 L 242.18815104166663 134.93952000000002 L 216.69466145833331 134.93952000000002 z" cy="98.46892000000001" cx="267.681640625" j="4" val="20" barHeight="36.4696" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2545" d="M 267.681640625 145.8804 L 267.681640625 118.52820000000001 L 293.1751302083333 118.52820000000001 L 293.1751302083333 145.8804 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 267.681640625 145.8804 L 267.681640625 118.52820000000001 L 293.1751302083333 118.52820000000001 L 293.1751302083333 145.8804 z" pathFrom="M 267.681640625 145.8804 L 267.681640625 145.8804 L 293.1751302083333 145.8804 L 293.1751302083333 145.8804 L 293.1751302083333 145.8804 L 293.1751302083333 145.8804 L 293.1751302083333 145.8804 L 267.681640625 145.8804 z" cy="118.52720000000001" cx="318.6686197916667" j="5" val="15" barHeight="27.3522" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2551" d="M 318.6686197916667 109.41080000000002 L 318.6686197916667 72.94120000000002 L 344.162109375 72.94120000000002 L 344.162109375 109.41080000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 318.6686197916667 109.41080000000002 L 318.6686197916667 72.94120000000002 L 344.162109375 72.94120000000002 L 344.162109375 109.41080000000002 z" pathFrom="M 318.6686197916667 109.41080000000002 L 318.6686197916667 109.41080000000002 L 344.162109375 109.41080000000002 L 344.162109375 109.41080000000002 L 344.162109375 109.41080000000002 L 344.162109375 109.41080000000002 L 344.162109375 109.41080000000002 L 318.6686197916667 109.41080000000002 z" cy="72.94020000000002" cx="369.65559895833337" j="6" val="20" barHeight="36.4696" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2557" d="M 369.65559895833337 145.8804 L 369.65559895833337 127.6456 L 395.1490885416667 127.6456 L 395.1490885416667 145.8804 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 369.65559895833337 145.8804 L 369.65559895833337 127.6456 L 395.1490885416667 127.6456 L 395.1490885416667 145.8804 z" pathFrom="M 369.65559895833337 145.8804 L 369.65559895833337 145.8804 L 395.1490885416667 145.8804 L 395.1490885416667 145.8804 L 395.1490885416667 145.8804 L 395.1490885416667 145.8804 L 395.1490885416667 145.8804 L 369.65559895833337 145.8804 z" cy="127.6446" cx="420.64257812500006" j="7" val="10" barHeight="18.2348" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2563" d="M 420.64257812500006 91.17600000000002 L 420.64257812500006 45.589000000000006 L 446.13606770833337 45.589000000000006 L 446.13606770833337 91.17600000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 420.64257812500006 91.17600000000002 L 420.64257812500006 45.589000000000006 L 446.13606770833337 45.589000000000006 L 446.13606770833337 91.17600000000002 z" pathFrom="M 420.64257812500006 91.17600000000002 L 420.64257812500006 91.17600000000002 L 446.13606770833337 91.17600000000002 L 446.13606770833337 91.17600000000002 L 446.13606770833337 91.17600000000002 L 446.13606770833337 91.17600000000002 L 446.13606770833337 91.17600000000002 L 420.64257812500006 91.17600000000002 z" cy="45.58800000000001" cx="471.62955729166674" j="8" val="25" barHeight="45.587" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2569" d="M 471.62955729166674 153.17432000000002 L 471.62955729166674 134.93952000000002 L 497.12304687500006 134.93952000000002 L 497.12304687500006 153.17432000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 471.62955729166674 153.17432000000002 L 471.62955729166674 134.93952000000002 L 497.12304687500006 134.93952000000002 L 497.12304687500006 153.17432000000002 z" pathFrom="M 471.62955729166674 153.17432000000002 L 471.62955729166674 153.17432000000002 L 497.12304687500006 153.17432000000002 L 497.12304687500006 153.17432000000002 L 497.12304687500006 153.17432000000002 L 497.12304687500006 153.17432000000002 L 497.12304687500006 153.17432000000002 L 471.62955729166674 153.17432000000002 z" cy="134.93852" cx="522.6165364583334" j="9" val="10" barHeight="18.2348" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2575" d="M 522.6165364583334 164.11520000000002 L 522.6165364583334 153.17432000000002 L 548.1100260416667 153.17432000000002 L 548.1100260416667 164.11520000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 522.6165364583334 164.11520000000002 L 522.6165364583334 153.17432000000002 L 548.1100260416667 153.17432000000002 L 548.1100260416667 164.11520000000002 z" pathFrom="M 522.6165364583334 164.11520000000002 L 522.6165364583334 164.11520000000002 L 548.1100260416667 164.11520000000002 L 548.1100260416667 164.11520000000002 L 548.1100260416667 164.11520000000002 L 548.1100260416667 164.11520000000002 L 548.1100260416667 164.11520000000002 L 522.6165364583334 164.11520000000002 z" cy="153.17332000000002" cx="573.603515625" j="10" val="6" barHeight="10.94088" barWidth="25.49348958333333"></path>
                                             <path id="SvgjsPath2581" d="M 573.603515625 109.41080000000002 L 573.603515625 72.94120000000002 L 599.0970052083334 72.94120000000002 L 599.0970052083334 109.41080000000002 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskj3nzualp)" pathTo="M 573.603515625 109.41080000000002 L 573.603515625 72.94120000000002 L 599.0970052083334 72.94120000000002 L 599.0970052083334 109.41080000000002 z" pathFrom="M 573.603515625 109.41080000000002 L 573.603515625 109.41080000000002 L 599.0970052083334 109.41080000000002 L 599.0970052083334 109.41080000000002 L 599.0970052083334 109.41080000000002 L 599.0970052083334 109.41080000000002 L 599.0970052083334 109.41080000000002 L 573.603515625 109.41080000000002 z" cy="72.94020000000002" cx="624.5904947916666" j="11" val="20" barHeight="36.4696" barWidth="25.49348958333333"></path>
                                             <g id="SvgjsG2513" class="apexcharts-bar-goals-markers" style="pointer-events: none">
                                                <g id="SvgjsG2514" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2520" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2526" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2532" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2538" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2544" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2550" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2556" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2562" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2568" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2574" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                                <g id="SvgjsG2580" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskj3nzualp)"></g>
                                             </g>
                                          </g>
                                          <g id="SvgjsG2437" class="apexcharts-datalabels" data:realIndex="0">
                                             <g id="SvgjsG2442" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2444" font-family="Helvetica, Arial, sans-serif" x="25.49348958333333" y="166.55450000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="25.49348958333333" cy="166.55450000000002" style="font-family: Helvetica, Arial, sans-serif;">25</text>
                                             </g>
                                             <g id="SvgjsG2448" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2450" font-family="Helvetica, Arial, sans-serif" x="76.48046875" y="157.43710000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="76.48046875" cy="157.43710000000002" style="font-family: Helvetica, Arial, sans-serif;">35</text>
                                             </g>
                                             <g id="SvgjsG2454" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2456" font-family="Helvetica, Arial, sans-serif" x="127.46744791666666" y="143.76100000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="127.46744791666666" cy="143.76100000000002" style="font-family: Helvetica, Arial, sans-serif;">50</text>
                                             </g>
                                             <g id="SvgjsG2460" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2462" font-family="Helvetica, Arial, sans-serif" x="178.45442708333331" y="134.64360000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="178.45442708333331" cy="134.64360000000002" style="font-family: Helvetica, Arial, sans-serif;">60</text>
                                             </g>
                                             <g id="SvgjsG2466" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2468" font-family="Helvetica, Arial, sans-serif" x="229.44140625" y="165.64276" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="229.44140625" cy="165.64276" style="font-family: Helvetica, Arial, sans-serif;">26</text>
                                             </g>
                                             <g id="SvgjsG2472" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2474" font-family="Helvetica, Arial, sans-serif" x="280.4283854166667" y="171.1132" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="280.4283854166667" cy="171.1132" style="font-family: Helvetica, Arial, sans-serif;">20</text>
                                             </g>
                                             <g id="SvgjsG2478" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2480" font-family="Helvetica, Arial, sans-serif" x="331.41536458333337" y="152.8784" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="331.41536458333337" cy="152.8784" style="font-family: Helvetica, Arial, sans-serif;">40</text>
                                             </g>
                                             <g id="SvgjsG2484" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2486" font-family="Helvetica, Arial, sans-serif" x="382.40234375000006" y="171.1132" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="382.40234375000006" cy="171.1132" style="font-family: Helvetica, Arial, sans-serif;">20</text>
                                             </g>
                                             <g id="SvgjsG2490" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2492" font-family="Helvetica, Arial, sans-serif" x="433.38932291666674" y="143.76100000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="433.38932291666674" cy="143.76100000000002" style="font-family: Helvetica, Arial, sans-serif;">50</text>
                                             </g>
                                             <g id="SvgjsG2496" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2498" font-family="Helvetica, Arial, sans-serif" x="484.37630208333337" y="174.76016" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="484.37630208333337" cy="174.76016" style="font-family: Helvetica, Arial, sans-serif;">16</text>
                                             </g>
                                             <g id="SvgjsG2502" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2504" font-family="Helvetica, Arial, sans-serif" x="535.36328125" y="180.2306" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="535.36328125" cy="180.2306" style="font-family: Helvetica, Arial, sans-serif;">10</text>
                                             </g>
                                             <g id="SvgjsG2508" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2510" font-family="Helvetica, Arial, sans-serif" x="586.3502604166666" y="152.8784" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="586.3502604166666" cy="152.8784" style="font-family: Helvetica, Arial, sans-serif;">40</text>
                                             </g>
                                          </g>
                                          <g id="SvgjsG2512" class="apexcharts-datalabels" data:realIndex="1">
                                             <g id="SvgjsG2517" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2519" font-family="Helvetica, Arial, sans-serif" x="25.49348958333333" y="130.08590000000004" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="25.49348958333333" cy="130.08590000000004" style="font-family: Helvetica, Arial, sans-serif;">15</text>
                                             </g>
                                             <g id="SvgjsG2523" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2525" font-family="Helvetica, Arial, sans-serif" x="76.48046875" y="110.93936000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="76.48046875" cy="110.93936000000002" style="font-family: Helvetica, Arial, sans-serif;">16</text>
                                             </g>
                                             <g id="SvgjsG2529" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2531" font-family="Helvetica, Arial, sans-serif" x="127.46744791666666" y="76.29324000000001" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="127.46744791666666" cy="76.29324000000001" style="font-family: Helvetica, Arial, sans-serif;">24</text>
                                             </g>
                                             <g id="SvgjsG2535" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2537" font-family="Helvetica, Arial, sans-serif" x="178.45442708333331" y="52.58800000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="178.45442708333331" cy="52.58800000000002" style="font-family: Helvetica, Arial, sans-serif;">30</text>
                                             </g>
                                             <g id="SvgjsG2541" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2543" font-family="Helvetica, Arial, sans-serif" x="229.44140625" y="123.70372" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="229.44140625" cy="123.70372" style="font-family: Helvetica, Arial, sans-serif;">20</text>
                                             </g>
                                             <g id="SvgjsG2547" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2549" font-family="Helvetica, Arial, sans-serif" x="280.4283854166667" y="139.2033" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="280.4283854166667" cy="139.2033" style="font-family: Helvetica, Arial, sans-serif;">15</text>
                                             </g>
                                             <g id="SvgjsG2553" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2555" font-family="Helvetica, Arial, sans-serif" x="331.41536458333337" y="98.17500000000001" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="331.41536458333337" cy="98.17500000000001" style="font-family: Helvetica, Arial, sans-serif;">20</text>
                                             </g>
                                             <g id="SvgjsG2559" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2561" font-family="Helvetica, Arial, sans-serif" x="382.40234375000006" y="143.762" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="382.40234375000006" cy="143.762" style="font-family: Helvetica, Arial, sans-serif;">10</text>
                                             </g>
                                             <g id="SvgjsG2565" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2567" font-family="Helvetica, Arial, sans-serif" x="433.38932291666674" y="75.38150000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="433.38932291666674" cy="75.38150000000002" style="font-family: Helvetica, Arial, sans-serif;">25</text>
                                             </g>
                                             <g id="SvgjsG2571" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2573" font-family="Helvetica, Arial, sans-serif" x="484.37630208333337" y="151.05592000000001" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="484.37630208333337" cy="151.05592000000001" style="font-family: Helvetica, Arial, sans-serif;">10</text>
                                             </g>
                                             <g id="SvgjsG2577" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2579" font-family="Helvetica, Arial, sans-serif" x="535.36328125" y="165.64376000000001" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="535.36328125" cy="165.64376000000001" style="font-family: Helvetica, Arial, sans-serif;">6</text>
                                             </g>
                                             <g id="SvgjsG2583" class="apexcharts-data-labels" transform="rotate(0)">
                                                <text id="SvgjsText2585" font-family="Helvetica, Arial, sans-serif" x="586.3502604166666" y="98.17500000000001" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#ffffff" class="apexcharts-datalabel" cx="586.3502604166666" cy="98.17500000000001" style="font-family: Helvetica, Arial, sans-serif;">20</text>
                                             </g>
                                          </g>
                                       </g>
                                       <g id="SvgjsG2589" class="apexcharts-grid-borders">
                                          <line id="SvgjsLine2603" x1="0" y1="0" x2="611.84375" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                          <line id="SvgjsLine2608" x1="0" y1="182.348" x2="611.84375" y2="182.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                          <line id="SvgjsLine2651" x1="0" y1="183.348" x2="611.84375" y2="183.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"></line>
                                       </g>
                                       <line id="SvgjsLine2611" x1="0" y1="0" x2="611.84375" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                       <line id="SvgjsLine2612" x1="0" y1="0" x2="611.84375" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                       <g id="SvgjsG2613" class="apexcharts-xaxis" transform="translate(0, 0)">
                                          <g id="SvgjsG2614" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)">
                                             <text id="SvgjsText2616" font-family="Helvetica, Arial, sans-serif" x="25.493489583333332" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2617">Jan</tspan>
                                                <title>Jan</title>
                                             </text>
                                             <text id="SvgjsText2619" font-family="Helvetica, Arial, sans-serif" x="76.48046875" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2620">Feb</tspan>
                                                <title>Feb</title>
                                             </text>
                                             <text id="SvgjsText2622" font-family="Helvetica, Arial, sans-serif" x="127.46744791666667" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2623">Mar</tspan>
                                                <title>Mar</title>
                                             </text>
                                             <text id="SvgjsText2625" font-family="Helvetica, Arial, sans-serif" x="178.45442708333331" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2626">Apr</tspan>
                                                <title>Apr</title>
                                             </text>
                                             <text id="SvgjsText2628" font-family="Helvetica, Arial, sans-serif" x="229.44140624999997" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2629">May</tspan>
                                                <title>May</title>
                                             </text>
                                             <text id="SvgjsText2631" font-family="Helvetica, Arial, sans-serif" x="280.4283854166667" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2632">June</tspan>
                                                <title>June</title>
                                             </text>
                                             <text id="SvgjsText2634" font-family="Helvetica, Arial, sans-serif" x="331.41536458333337" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2635">July</tspan>
                                                <title>July</title>
                                             </text>
                                             <text id="SvgjsText2637" font-family="Helvetica, Arial, sans-serif" x="382.40234375000006" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2638">Aug</tspan>
                                                <title>Aug</title>
                                             </text>
                                             <text id="SvgjsText2640" font-family="Helvetica, Arial, sans-serif" x="433.38932291666674" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2641">Sep</tspan>
                                                <title>Sep</title>
                                             </text>
                                             <text id="SvgjsText2643" font-family="Helvetica, Arial, sans-serif" x="484.3763020833334" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2644">Oct</tspan>
                                                <title>Oct</title>
                                             </text>
                                             <text id="SvgjsText2646" font-family="Helvetica, Arial, sans-serif" x="535.36328125" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2647">Nov</tspan>
                                                <title>Nov</title>
                                             </text>
                                             <text id="SvgjsText2649" font-family="Helvetica, Arial, sans-serif" x="586.3502604166666" y="211.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan2650">Dec</tspan>
                                                <title>Dec</title>
                                             </text>
                                          </g>
                                       </g>
                                       <g id="SvgjsG2672" class="apexcharts-yaxis-annotations"></g>
                                       <g id="SvgjsG2673" class="apexcharts-xaxis-annotations"></g>
                                       <g id="SvgjsG2674" class="apexcharts-point-annotations"></g>
                                    </g>
                                 </svg>
                                 <div class="apexcharts-tooltip apexcharts-theme-light">
                                    <div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 1;">
                                       <span class="apexcharts-tooltip-marker" style="background-color: rgb(37, 161, 148);"></span>
                                       <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                          <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                          <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                          <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                       </div>
                                    </div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 2;">
                                       <span class="apexcharts-tooltip-marker" style="background-color: rgb(255, 122, 44);"></span>
                                       <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                          <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                          <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                          <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                    <div class="apexcharts-yaxistooltip-text"></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card h-100">
                     <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                           <h6 class="text-lg mb-0">Notice Board</h6>
                           <div class="dropdown">
                              <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                              </button>
                              <ul class="dropdown-menu p-12 border bg-base shadow">
                                 <li>
                                    <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                                       <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                                       View
                                    </button>
                                 </li>
                                 <li>
                                    <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                                       <iconify-icon icon="lucide:edit" class="icon text-lg line-height-1"></iconify-icon>
                                       Edit
                                    </button>
                                 </li>
                                 <li>
                                    <button type="button" class="delete-item dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                                       <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                                       Delete
                                    </button>
                                 </li>
                              </ul>
                           </div>
                        </div>
                        <div class="ps-20 pt-20 pb-20">
                           <div class="pe-20 d-flex flex-column gap-20 max-h-462-px overflow-y-auto scroll-sm">
                              <div class="d-flex align-items-start gap-16">
                                 <img src="assets/images/thumbs/notice-board-img1.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                 <div class="">
                                    <h6 class="mb-4 text-lg">Admin</h6>
                                    <p class="text-secondary-light text-sm mb-0">Lorem Ipsum is simply dummy text of the
                                       printing and typesetti
                                    </p>
                                    <span class="text-secondary-light text-sm mb-0 mt-4">25 Jan 2024</span>
                                 </div>
                              </div>
                              <div class="d-flex align-items-start gap-16">
                                 <img src="assets/images/thumbs/notice-board-img2.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                 <div class="">
                                    <h6 class="mb-4 text-lg">Kathryn Murphy</h6>
                                    <p class="text-secondary-light text-sm mb-0">Lorem Ipsum is simply dummy text of the
                                       printing and typesett ing industry Lorem Ipsum is simply dummy text of the printing and
                                       typesetting industry.
                                    </p>
                                    <span class="text-secondary-light text-sm mb-0 mt-4">25 Jan 2024</span>
                                 </div>
                              </div>
                              <div class="d-flex align-items-start gap-16">
                                 <img src="assets/images/thumbs/notice-board-img3.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                 <div class="">
                                    <h6 class="mb-4 text-lg">Admin</h6>
                                    <p class="text-secondary-light text-sm mb-0">Lorem Ipsum is simply dummy text of the
                                       printing and typesetti
                                    </p>
                                    <span class="text-secondary-light text-sm mb-0 mt-4">25 Jan 2024</span>
                                 </div>
                              </div>
                              <div class="d-flex align-items-start gap-16">
                                 <img src="assets/images/thumbs/notice-board-img2.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                 <div class="">
                                    <h6 class="mb-4 text-lg">John Doe</h6>
                                    <p class="text-secondary-light text-sm mb-0">Lorem ipsum dolor sit amet consectetur
                                       adipisicing elit. Laborum voluptas corporis qui dolore est odit officia fuga?
                                    </p>
                                    <span class="text-secondary-light text-sm mb-0 mt-4">25 Jan 2024</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card h-100">
                     <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                           <h6 class="text-lg mb-0">Leave Requests</h6>
                           <div class="dropdown">
                              <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                              </button>
                              <ul class="dropdown-menu p-12 border bg-base shadow">
                                 <li>
                                    <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                                       <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                                       View
                                    </button>
                                 </li>
                                 <li>
                                    <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                                       <iconify-icon icon="lucide:edit" class="icon text-lg line-height-1"></iconify-icon>
                                       Edit
                                    </button>
                                 </li>
                                 <li>
                                    <button type="button" class="delete-item dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                                       <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                                       Delete
                                    </button>
                                 </li>
                              </ul>
                           </div>
                        </div>
                        <div class="ps-20 pt-20 pb-20">
                           <div class="pe-20 d-flex flex-column gap-28 max-h-462-px overflow-y-auto scroll-sm">
                              <div class="d-flex align-items-center justify-content-between gap-16">
                                 <div class="d-flex align-items-start gap-16">
                                    <img src="assets/images/thumbs/leave-request-img1.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                    <div class="">
                                       <h6 class="mb-0 text-lg">Darlene Robertson</h6>
                                       <span class="text-secondary-light text-sm mb-0">English Teacher</span>
                                    </div>
                                 </div>
                                 <div class="text-end">
                                    <span class="d-block fw-bold text-primary-light">3 Days</span>
                                    <p class="text-secondary-light text-sm mb-0">Apply on: 10 April</p>
                                 </div>
                              </div>
                              <div class="d-flex align-items-center justify-content-between gap-16">
                                 <div class="d-flex align-items-start gap-16">
                                    <img src="assets/images/thumbs/leave-request-img2.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                    <div class="">
                                       <h6 class="mb-0 text-lg">Esther Howard</h6>
                                       <span class="text-secondary-light text-sm mb-0">English Teacher</span>
                                    </div>
                                 </div>
                                 <div class="text-end">
                                    <span class="d-block fw-bold text-primary-light">3 Days</span>
                                    <p class="text-secondary-light text-sm mb-0">Apply on: 10 April</p>
                                 </div>
                              </div>
                              <div class="d-flex align-items-center justify-content-between gap-16">
                                 <div class="d-flex align-items-start gap-16">
                                    <img src="assets/images/thumbs/leave-request-img3.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                    <div class="">
                                       <h6 class="mb-0 text-lg">Kristin Watson</h6>
                                       <span class="text-secondary-light text-sm mb-0">English Teacher</span>
                                    </div>
                                 </div>
                                 <div class="text-end">
                                    <span class="d-block fw-bold text-primary-light">3 Days</span>
                                    <p class="text-secondary-light text-sm mb-0">Apply on: 10 April</p>
                                 </div>
                              </div>
                              <div class="d-flex align-items-center justify-content-between gap-16">
                                 <div class="d-flex align-items-start gap-16">
                                    <img src="assets/images/thumbs/leave-request-img4.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                    <div class="">
                                       <h6 class="mb-0 text-lg">Leslie Alexander</h6>
                                       <span class="text-secondary-light text-sm mb-0">English Teacher</span>
                                    </div>
                                 </div>
                                 <div class="text-end">
                                    <span class="d-block fw-bold text-primary-light">3 Days</span>
                                    <p class="text-secondary-light text-sm mb-0">Apply on: 10 April</p>
                                 </div>
                              </div>
                              <div class="d-flex align-items-center justify-content-between gap-16">
                                 <div class="d-flex align-items-start gap-16">
                                    <img src="assets/images/thumbs/leave-request-img5.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                    <div class="">
                                       <h6 class="mb-0 text-lg">Dianne Russell</h6>
                                       <span class="text-secondary-light text-sm mb-0">English Teacher</span>
                                    </div>
                                 </div>
                                 <div class="text-end">
                                    <span class="d-block fw-bold text-primary-light">3 Days</span>
                                    <p class="text-secondary-light text-sm mb-0">Apply on: 10 April</p>
                                 </div>
                              </div>
                              <div class="d-flex align-items-center justify-content-between gap-16">
                                 <div class="d-flex align-items-start gap-16">
                                    <img src="assets/images/thumbs/leave-request-img3.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                                    <div class="">
                                       <h6 class="mb-0 text-lg">Kristin Watson</h6>
                                       <span class="text-secondary-light text-sm mb-0">English Teacher</span>
                                    </div>
                                 </div>
                                 <div class="text-end">
                                    <span class="d-block fw-bold text-primary-light">3 Days</span>
                                    <p class="text-secondary-light text-sm mb-0">Apply on: 10 April</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-4">
            <div class="card h-100">
               <div class="card-body p-0">
                  <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
                     <h6 class="text-lg mb-0">Calendar</h6>
                  </div>
                  <div class="p-20">
                     <div class="calendar">
                        <div class="calendar__header">
                           <button type="button" class="calendar__arrow left">
                           <i class="ri-arrow-left-s-line"></i>
                           </button>
                           <p class="display text-md text-secondary-light fw-semibold mb-0">January 2026</p>
                           <button type="button" class="calendar__arrow right">
                           <i class="ri-arrow-right-s-line"></i>
                           </button>
                        </div>
                        <div class="calendar__week week">
                           <div class="calendar__week-text">Su</div>
                           <div class="calendar__week-text">Mo</div>
                           <div class="calendar__week-text">Tu</div>
                           <div class="calendar__week-text">We</div>
                           <div class="calendar__week-text">Th</div>
                           <div class="calendar__week-text">Fr</div>
                           <div class="calendar__week-text">Sa</div>
                        </div>
                        <div class="days">
                           <div></div>
                           <div></div>
                           <div></div>
                           <div></div>
                           <div data-date="Thu Jan 01 2026">1</div>
                           <div data-date="Fri Jan 02 2026">2</div>
                           <div data-date="Sat Jan 03 2026">3</div>
                           <div data-date="Sun Jan 04 2026">4</div>
                           <div data-date="Mon Jan 05 2026" class="current-date">5</div>
                           <div data-date="Tue Jan 06 2026">6</div>
                           <div data-date="Wed Jan 07 2026">7</div>
                           <div data-date="Thu Jan 08 2026">8</div>
                           <div data-date="Fri Jan 09 2026">9</div>
                           <div data-date="Sat Jan 10 2026">10</div>
                           <div data-date="Sun Jan 11 2026">11</div>
                           <div data-date="Mon Jan 12 2026">12</div>
                           <div data-date="Tue Jan 13 2026">13</div>
                           <div data-date="Wed Jan 14 2026">14</div>
                           <div data-date="Thu Jan 15 2026">15</div>
                           <div data-date="Fri Jan 16 2026">16</div>
                           <div data-date="Sat Jan 17 2026">17</div>
                           <div data-date="Sun Jan 18 2026">18</div>
                           <div data-date="Mon Jan 19 2026">19</div>
                           <div data-date="Tue Jan 20 2026">20</div>
                           <div data-date="Wed Jan 21 2026">21</div>
                           <div data-date="Thu Jan 22 2026">22</div>
                           <div data-date="Fri Jan 23 2026">23</div>
                           <div data-date="Sat Jan 24 2026">24</div>
                           <div data-date="Sun Jan 25 2026">25</div>
                           <div data-date="Mon Jan 26 2026">26</div>
                           <div data-date="Tue Jan 27 2026">27</div>
                           <div data-date="Wed Jan 28 2026">28</div>
                           <div data-date="Thu Jan 29 2026">29</div>
                           <div data-date="Fri Jan 30 2026">30</div>
                           <div data-date="Sat Jan 31 2026">31</div>
                        </div>
                     </div>
                  </div>
                  <div class="ps-20 pt-20 pb-20 border-top border-neutral-200">
                     <h6 class="text-lg mb-20">Upcoming Events</h6>
                     <div class="pe-20 d-flex flex-column gap-32 overflow-y-auto max-h-500-px scroll-sm">
                        <div class="d-flex align-items-center justify-content-between gap-16">
                           <div class="ps-10 border-start-width-3-px border-purple-600">
                              <div class="d-flex align-items-end gap-6">
                                 <h6 class="text-lg fw-normal mb-0">09:00 - 09:45</h6>
                                 <span class="text-xs text-secondary-light line-height-1 mb-2">AM</span>
                              </div>
                              <p class="text-secondary-light mt-4 mb-2 text-sm">Marketing Strategy Kickoff</p>
                              <p class="text-xs text-secondary-light mb-0">Lead by <a href="javascript:void(0)" class="text-primary-600 hover-underline">Robert Fox</a></p>
                           </div>
                           <div>
                              <a href="javascript:void(0)" class="py-6 px-16 radius-4 bg-neutral-100 text-secondary-light fw-semibold bg-hover-primary-600 hover-text-white">View</a>
                           </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-16">
                           <div class="ps-10 border-start-width-3-px border-warning-600">
                              <div class="d-flex align-items-end gap-6">
                                 <h6 class="text-lg fw-normal mb-0">11:15 - 12:00</h6>
                                 <span class="text-xs text-secondary-light line-height-1 mb-2">AM</span>
                              </div>
                              <p class="text-secondary-light mt-4 mb-2 text-sm">Product Design Brainstorm</p>
                              <p class="text-xs text-secondary-light mb-0">Lead by <a href="javascript:void(0)" class="text-primary-600 hover-underline">Leslie Alexander</a></p>
                           </div>
                           <div>
                              <a href="javascript:void(0)" class="py-6 px-16 radius-4 bg-neutral-100 text-secondary-light fw-semibold bg-hover-primary-600 hover-text-white">View</a>
                           </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-16">
                           <div class="ps-10 border-start-width-3-px border-blue-600">
                              <div class="d-flex align-items-end gap-6">
                                 <h6 class="text-lg fw-normal mb-0">02:00 - 03:00</h6>
                                 <span class="text-xs text-secondary-light line-height-1 mb-2">PM</span>
                              </div>
                              <p class="text-secondary-light mt-4 mb-2 text-sm">Client Feedback Review</p>
                              <p class="text-xs text-secondary-light mb-0">Lead by <a href="javascript:void(0)" class="text-primary-600 hover-underline">Courtney Henry</a></p>
                           </div>
                           <div>
                              <a href="javascript:void(0)" class="py-6 px-16 radius-4 bg-neutral-100 text-secondary-light fw-semibold bg-hover-primary-600 hover-text-white">View</a>
                           </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-16">
                           <div class="ps-10 border-start-width-3-px border-success-600">
                              <div class="d-flex align-items-end gap-6">
                                 <h6 class="text-lg fw-normal mb-0">04:15 - 05:00</h6>
                                 <span class="text-xs text-secondary-light line-height-1 mb-2">PM</span>
                              </div>
                              <p class="text-secondary-light mt-4 mb-2 text-sm">Sprint Planning &amp; Task Allocation</p>
                              <p class="text-xs text-secondary-light mb-0">Lead by <a href="javascript:void(0)" class="text-primary-600 hover-underline">Eleanor Pena</a></p>
                           </div>
                           <div>
                              <a href="javascript:void(0)" class="py-6 px-16 radius-4 bg-neutral-100 text-secondary-light fw-semibold bg-hover-primary-600 hover-text-white">View</a>
                           </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-16">
                           <div class="ps-10 border-start-width-3-px border-primary-600">
                              <div class="d-flex align-items-end gap-6">
                                 <h6 class="text-lg fw-normal mb-0">01:15 - 02:00</h6>
                                 <span class="text-xs text-secondary-light line-height-1 mb-2">PM</span>
                              </div>
                              <p class="text-secondary-light mt-4 mb-2 text-sm">Client Feedback Review</p>
                              <p class="text-xs text-secondary-light mb-0">Lead by <a href="javascript:void(0)" class="text-primary-600 hover-underline">John</a></p>
                           </div>
                           <div>
                              <a href="javascript:void(0)" class="py-6 px-16 radius-4 bg-neutral-100 text-secondary-light fw-semibold bg-hover-primary-600 hover-text-white">View</a>
                           </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-16">
                           <div class="ps-10 border-start-width-3-px border-warning-600">
                              <div class="d-flex align-items-end gap-6">
                                 <h6 class="text-lg fw-normal mb-0">11:15 - 12:00</h6>
                                 <span class="text-xs text-secondary-light line-height-1 mb-2">AM</span>
                              </div>
                              <p class="text-secondary-light mt-4 mb-2 text-sm">Product Design Brainstorm</p>
                              <p class="text-xs text-secondary-light mb-0">Lead by <a href="javascript:void(0)" class="text-primary-600 hover-underline">Leslie Alexander</a></p>
                           </div>
                           <div>
                              <a href="javascript:void(0)" class="py-6 px-16 radius-4 bg-neutral-100 text-secondary-light fw-semibold bg-hover-primary-600 hover-text-white">View</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xxl-4 col-lg-6">
      <div class="card h-100">
         <div class="card-body p-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
               <h6 class="text-lg mb-0">User Overview</h6>
               <div class="dropdown">
                  <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                  </button>
                  <ul class="dropdown-menu p-12 border bg-base shadow">
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                           <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                           View
                        </button>
                     </li>
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                           <iconify-icon icon="lucide:edit" class="icon text-lg line-height-1"></iconify-icon>
                           Edit
                        </button>
                     </li>
                     <li>
                        <button type="button" class="delete-item dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                           <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                           Delete
                        </button>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="p-20">
               <div>
                  <div class="mt-40 mb-24 pe-110 position-relative max-w-288-px mx-auto">
                     <div class="w-170-px h-170-px rounded-circle z-1 position-relative d-inline-flex justify-content-center align-items-center">
                        <img src="assets/images/icons/radial-bg1.png" alt="Image" class="position-absolute top-0 start-0 z-n1 w-100 h-100 object-fit-cover">
                        <h5 class="text-white"> 60% </h5>
                     </div>
                     <div class="w-144-px h-144-px rounded-circle z-1 position-relative d-inline-flex justify-content-center align-items-center position-absolute top-0 end-0 mt--36">
                        <img src="assets/images/icons/radial-bg2.png" alt="Image" class="position-absolute top-0 start-0 z-n1 w-100 h-100 object-fit-cover">
                        <h5 class="text-white"> 30% </h5>
                     </div>
                     <div class="w-110-px h-110-px rounded-circle z-1 position-relative d-inline-flex justify-content-center align-items-center position-absolute bottom-0 start-50 translate-middle-x ms-48">
                        <img src="assets/images/icons/radial-bg3.png" alt="Image" class="position-absolute top-0 start-0 z-n1 w-100 h-100 object-fit-cover">
                        <h5 class="text-white"> 10% </h5>
                     </div>
                  </div>
                  <div class="d-flex align-items-center flex-wrap gap-24 justify-content-evenly">
                     <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center gap-2">
                           <span class="w-12-px h-12-px rounded-pill bg-success-600"></span>
                           <span class="text-secondary-light text-sm fw-normal">Student</span>
                        </div>
                        <h6 class="text-primary-light fw-semibold mb-0 mt-4 text-lg">750</h6>
                     </div>
                     <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center gap-2">
                           <span class="w-12-px h-12-px rounded-pill bg-warning-600"></span>
                           <span class="text-secondary-light text-sm fw-normal">Teacher</span>
                        </div>
                        <h6 class="text-primary-light fw-semibold mb-0 mt-4 text-lg">56</h6>
                     </div>
                     <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center gap-2">
                           <span class="w-12-px h-12-px rounded-pill bg-blue-600"></span>
                           <span class="text-secondary-light text-sm fw-normal">Staffs </span>
                        </div>
                        <h6 class="text-primary-light fw-semibold mb-0 mt-4 text-lg">15</h6>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xxl-8 col-lg-6">
      <div class="card h-100">
         <div class="card-body p-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
               <h6 class="text-lg mb-0">Income Vs Expense </h6>
               <div class="dropdown">
                  <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                  </button>
                  <ul class="dropdown-menu p-12 border bg-base shadow">
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                           <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                           View
                        </button>
                     </li>
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                           <iconify-icon icon="lucide:edit" class="icon text-lg line-height-1"></iconify-icon>
                           Edit
                        </button>
                     </li>
                     <li>
                        <button type="button" class="delete-item dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                           <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                           Delete
                        </button>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="p-20">
               <ul class="d-flex flex-wrap align-items-center justify-content-center mb-16 gap-3">
                  <li class="d-flex align-items-center gap-8">
                     <span class="w-12-px h-12-px rounded-circle bg-primary-600"></span>
                     <span class="text-secondary-light text-sm fw-semibold">
                     Income:
                     <span class="text-primary-light fw-bold">$500</span>
                     </span>
                  </li>
                  <li class="d-flex align-items-center gap-8">
                     <span class="w-12-px h-12-px rounded-circle bg-warning-600"></span>
                     <span class="text-secondary-light text-sm font-semibold">
                     Expense:
                     <span class="text-primary-light fw-bold"> $300</span>
                     </span>
                  </li>
               </ul>
               <div id="incomeExpense" class="apexcharts-tooltip-style-1" style="min-height: 275px;">
                  <div id="apexchartsagymeky9" class="apexcharts-canvas apexchartsagymeky9 apexcharts-theme-light" style="width: 687px; height: 260px;">
                     <svg id="SvgjsSvg2677" width="687" height="260" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                        <foreignObject x="0" y="0" width="687" height="260">
                           <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 130px;"></div>
                        </foreignObject>
                        <rect id="SvgjsRect2682" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                        <g id="SvgjsG2760" class="apexcharts-yaxis" rel="0" transform="translate(27.359375, 0)">
                           <g id="SvgjsG2761" class="apexcharts-yaxis-texts-g">
                              <text id="SvgjsText2763" font-family="Helvetica, Arial, sans-serif" x="20" y="11.7" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2764">$70k</tspan>
                                 <title>$70k</title>
                              </text>
                              <text id="SvgjsText2766" font-family="Helvetica, Arial, sans-serif" x="20" y="43.001714285714286" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2767">$60k</tspan>
                                 <title>$60k</title>
                              </text>
                              <text id="SvgjsText2769" font-family="Helvetica, Arial, sans-serif" x="20" y="74.30342857142857" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2770">$50k</tspan>
                                 <title>$50k</title>
                              </text>
                              <text id="SvgjsText2772" font-family="Helvetica, Arial, sans-serif" x="20" y="105.60514285714285" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2773">$40k</tspan>
                                 <title>$40k</title>
                              </text>
                              <text id="SvgjsText2775" font-family="Helvetica, Arial, sans-serif" x="20" y="136.90685714285712" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2776">$30k</tspan>
                                 <title>$30k</title>
                              </text>
                              <text id="SvgjsText2778" font-family="Helvetica, Arial, sans-serif" x="20" y="168.20857142857142" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2779">$20k</tspan>
                                 <title>$20k</title>
                              </text>
                              <text id="SvgjsText2781" font-family="Helvetica, Arial, sans-serif" x="20" y="199.51028571428571" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2782">$10k</tspan>
                                 <title>$10k</title>
                              </text>
                              <text id="SvgjsText2784" font-family="Helvetica, Arial, sans-serif" x="20" y="230.812" text-anchor="end" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                 <tspan id="SvgjsTspan2785">$0k</tspan>
                                 <title>$0k</title>
                              </text>
                           </g>
                        </g>
                        <g id="SvgjsG2679" class="apexcharts-inner apexcharts-graphical" transform="translate(45.359375, 10)">
                           <defs id="SvgjsDefs2678">
                              <clipPath id="gridRectMaskagymeky9">
                                 <rect id="SvgjsRect2684" width="634.1796875" height="221.112" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                              </clipPath>
                              <clipPath id="forecastMaskagymeky9"></clipPath>
                              <clipPath id="nonForecastMaskagymeky9"></clipPath>
                              <clipPath id="gridRectMarkerMaskagymeky9">
                                 <rect id="SvgjsRect2685" width="632.1796875" height="223.112" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                              </clipPath>
                              <linearGradient id="SvgjsLinearGradient2690" x1="0" y1="0" x2="0" y2="1">
                                 <stop id="SvgjsStop2691" stop-opacity="0.4" stop-color="rgba(22,163,74,0.4)" offset="0"></stop>
                                 <stop id="SvgjsStop2692" stop-opacity="0.05" stop-color="rgba(139,209,165,0.05)" offset="1"></stop>
                                 <stop id="SvgjsStop2693" stop-opacity="0.05" stop-color="rgba(139,209,165,0.05)" offset="1"></stop>
                              </linearGradient>
                              <linearGradient id="SvgjsLinearGradient2699" x1="0" y1="0" x2="0" y2="1">
                                 <stop id="SvgjsStop2700" stop-opacity="0.4" stop-color="rgba(255,159,41,0.4)" offset="0"></stop>
                                 <stop id="SvgjsStop2701" stop-opacity="0.05" stop-color="rgba(255,207,148,0.05)" offset="1"></stop>
                                 <stop id="SvgjsStop2702" stop-opacity="0.05" stop-color="rgba(255,207,148,0.05)" offset="1"></stop>
                              </linearGradient>
                           </defs>
                           <line id="SvgjsLine2683" x1="0" y1="0" x2="0" y2="219.112" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="219.112" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line>
                           <line id="SvgjsLine2709" x1="0" y1="220.112" x2="0" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2710" x1="78.5224609375" y1="220.112" x2="78.5224609375" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2711" x1="157.044921875" y1="220.112" x2="157.044921875" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2712" x1="235.5673828125" y1="220.112" x2="235.5673828125" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2713" x1="314.08984375" y1="220.112" x2="314.08984375" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2714" x1="392.6123046875" y1="220.112" x2="392.6123046875" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2715" x1="471.134765625" y1="220.112" x2="471.134765625" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2716" x1="549.6572265625" y1="220.112" x2="549.6572265625" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <line id="SvgjsLine2717" x1="628.1796875" y1="220.112" x2="628.1796875" y2="226.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                           <g id="SvgjsG2705" class="apexcharts-grid">
                              <g id="SvgjsG2706" class="apexcharts-gridlines-horizontal">
                                 <line id="SvgjsLine2719" x1="0" y1="31.301714285714286" x2="628.1796875" y2="31.301714285714286" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                 <line id="SvgjsLine2720" x1="0" y1="62.60342857142857" x2="628.1796875" y2="62.60342857142857" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                 <line id="SvgjsLine2721" x1="0" y1="93.90514285714286" x2="628.1796875" y2="93.90514285714286" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                 <line id="SvgjsLine2722" x1="0" y1="125.20685714285715" x2="628.1796875" y2="125.20685714285715" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                 <line id="SvgjsLine2723" x1="0" y1="156.50857142857143" x2="628.1796875" y2="156.50857142857143" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                 <line id="SvgjsLine2724" x1="0" y1="187.81028571428573" x2="628.1796875" y2="187.81028571428573" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                 <line id="SvgjsLine2725" x1="0" y1="219.11200000000002" x2="628.1796875" y2="219.11200000000002" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                              </g>
                              <g id="SvgjsG2707" class="apexcharts-gridlines-vertical"></g>
                              <line id="SvgjsLine2727" x1="0" y1="219.112" x2="628.1796875" y2="219.112" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                              <line id="SvgjsLine2726" x1="0" y1="1" x2="0" y2="219.112" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                           </g>
                           <g id="SvgjsG2686" class="apexcharts-area-series apexcharts-plot-series">
                              <g id="SvgjsG2687" class="apexcharts-series" seriesName="Income" data:longestSeries="true" rel="1" data:realIndex="0">
                                 <path id="SvgjsPath2694" d="M 0 219.112 L 0 68.86377142857143 H 78.5224609375 V 109.556 H 157.044921875 V 46.95257142857142 H 235.5673828125 V 118.94651428571429 H 314.08984375 V 68.86377142857143 H 392.6123046875 V 125.20685714285713 H 471.134765625 V 172.15942857142858 H 549.6572265625 V 62.603428571428566 H 628.1796875 V 40.69222857142856 L 628.1796875 219.112M 628.1796875 40.69222857142856z" fill="url(#SvgjsLinearGradient2690)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskagymeky9)" pathTo="M 0 219.112 L 0 68.86377142857143 H 78.5224609375 V 109.556 H 157.044921875 V 46.95257142857142 H 235.5673828125 V 118.94651428571429 H 314.08984375 V 68.86377142857143 H 392.6123046875 V 125.20685714285713 H 471.134765625 V 172.15942857142858 H 549.6572265625 V 62.603428571428566 H 628.1796875 V 40.69222857142856 L 628.1796875 219.112M 628.1796875 40.69222857142856z" pathFrom="M -1 219.112 L -1 219.112 L 78.5224609375 219.112 L 157.044921875 219.112 L 235.5673828125 219.112 L 314.08984375 219.112 L 392.6123046875 219.112 L 471.134765625 219.112 L 549.6572265625 219.112 L 628.1796875 219.112"></path>
                                 <path id="SvgjsPath2695" d="M 0 68.86377142857143 H 78.5224609375 V 109.556 H 157.044921875 V 46.95257142857142 H 235.5673828125 V 118.94651428571429 H 314.08984375 V 68.86377142857143 H 392.6123046875 V 125.20685714285713 H 471.134765625 V 172.15942857142858 H 549.6572265625 V 62.603428571428566 H 628.1796875 V 40.69222857142856" fill="none" fill-opacity="1" stroke="#16a34a" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskagymeky9)" pathTo="M 0 68.86377142857143 H 78.5224609375 V 109.556 H 157.044921875 V 46.95257142857142 H 235.5673828125 V 118.94651428571429 H 314.08984375 V 68.86377142857143 H 392.6123046875 V 125.20685714285713 H 471.134765625 V 172.15942857142858 H 549.6572265625 V 62.603428571428566 H 628.1796875 V 40.69222857142856" pathFrom="M -1 219.112 L -1 219.112 L 78.5224609375 219.112 L 157.044921875 219.112 L 235.5673828125 219.112 L 314.08984375 219.112 L 392.6123046875 219.112 L 471.134765625 219.112 L 549.6572265625 219.112 L 628.1796875 219.112" fill-rule="evenodd"></path>
                                 <g id="SvgjsG2688" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0">
                                    <g class="apexcharts-series-markers">
                                       <circle id="SvgjsCircle2789" r="0" cx="0" cy="0" class="apexcharts-marker wza9alr51 no-pointer-events" stroke="#ffffff" fill="#16a34a" fill-opacity="1" stroke-width="1" stroke-opacity="0.9" default-marker-size="0"></circle>
                                    </g>
                                 </g>
                              </g>
                              <g id="SvgjsG2696" class="apexcharts-series" seriesName="Expense" data:longestSeries="true" rel="2" data:realIndex="1">
                                 <path id="SvgjsPath2703" d="M 0 219.112 L 0 181.54994285714287 H 78.5224609375 V 156.50857142857143 H 157.044921875 V 172.15942857142858 H 235.5673828125 V 137.72754285714285 H 314.08984375 V 150.24822857142857 H 392.6123046875 V 31.30171428571427 H 471.134765625 V 93.90514285714285 H 549.6572265625 V 118.94651428571429 H 628.1796875 V 140.85771428571428 L 628.1796875 219.112M 628.1796875 140.85771428571428z" fill="url(#SvgjsLinearGradient2699)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="1" clip-path="url(#gridRectMaskagymeky9)" pathTo="M 0 219.112 L 0 181.54994285714287 H 78.5224609375 V 156.50857142857143 H 157.044921875 V 172.15942857142858 H 235.5673828125 V 137.72754285714285 H 314.08984375 V 150.24822857142857 H 392.6123046875 V 31.30171428571427 H 471.134765625 V 93.90514285714285 H 549.6572265625 V 118.94651428571429 H 628.1796875 V 140.85771428571428 L 628.1796875 219.112M 628.1796875 140.85771428571428z" pathFrom="M -1 219.112 L -1 219.112 L 78.5224609375 219.112 L 157.044921875 219.112 L 235.5673828125 219.112 L 314.08984375 219.112 L 392.6123046875 219.112 L 471.134765625 219.112 L 549.6572265625 219.112 L 628.1796875 219.112"></path>
                                 <path id="SvgjsPath2704" d="M 0 181.54994285714287 H 78.5224609375 V 156.50857142857143 H 157.044921875 V 172.15942857142858 H 235.5673828125 V 137.72754285714285 H 314.08984375 V 150.24822857142857 H 392.6123046875 V 31.30171428571427 H 471.134765625 V 93.90514285714285 H 549.6572265625 V 118.94651428571429 H 628.1796875 V 140.85771428571428" fill="none" fill-opacity="1" stroke="#ff9f29" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="1" clip-path="url(#gridRectMaskagymeky9)" pathTo="M 0 181.54994285714287 H 78.5224609375 V 156.50857142857143 H 157.044921875 V 172.15942857142858 H 235.5673828125 V 137.72754285714285 H 314.08984375 V 150.24822857142857 H 392.6123046875 V 31.30171428571427 H 471.134765625 V 93.90514285714285 H 549.6572265625 V 118.94651428571429 H 628.1796875 V 140.85771428571428" pathFrom="M -1 219.112 L -1 219.112 L 78.5224609375 219.112 L 157.044921875 219.112 L 235.5673828125 219.112 L 314.08984375 219.112 L 392.6123046875 219.112 L 471.134765625 219.112 L 549.6572265625 219.112 L 628.1796875 219.112" fill-rule="evenodd"></path>
                                 <g id="SvgjsG2697" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="1">
                                    <g class="apexcharts-series-markers">
                                       <circle id="SvgjsCircle2790" r="0" cx="0" cy="0" class="apexcharts-marker w5q9l74ht no-pointer-events" stroke="#ffffff" fill="#ff9f29" fill-opacity="1" stroke-width="1" stroke-opacity="0.9" default-marker-size="0"></circle>
                                    </g>
                                 </g>
                              </g>
                              <g id="SvgjsG2689" class="apexcharts-datalabels" data:realIndex="0"></g>
                              <g id="SvgjsG2698" class="apexcharts-datalabels" data:realIndex="1"></g>
                           </g>
                           <g id="SvgjsG2708" class="apexcharts-grid-borders">
                              <line id="SvgjsLine2718" x1="0" y1="0" x2="628.1796875" y2="0" stroke="#d1d5db" stroke-dasharray="1" stroke-linecap="butt" class="apexcharts-gridline"></line>
                              <line id="SvgjsLine2759" x1="0" y1="220.112" x2="628.1796875" y2="220.112" stroke="#e0e0e0" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"></line>
                           </g>
                           <line id="SvgjsLine2728" x1="0" y1="0" x2="628.1796875" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                           <line id="SvgjsLine2729" x1="0" y1="0" x2="628.1796875" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                           <g id="SvgjsG2730" class="apexcharts-xaxis" transform="translate(0, 0)">
                              <g id="SvgjsG2731" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)">
                                 <text id="SvgjsText2733" font-family="Helvetica, Arial, sans-serif" x="0" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2734">Jan</tspan>
                                    <title>Jan</title>
                                 </text>
                                 <text id="SvgjsText2736" font-family="Helvetica, Arial, sans-serif" x="78.5224609375" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2737">Feb</tspan>
                                    <title>Feb</title>
                                 </text>
                                 <text id="SvgjsText2739" font-family="Helvetica, Arial, sans-serif" x="157.044921875" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2740">Mar</tspan>
                                    <title>Mar</title>
                                 </text>
                                 <text id="SvgjsText2742" font-family="Helvetica, Arial, sans-serif" x="235.5673828125" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2743">Apr</tspan>
                                    <title>Apr</title>
                                 </text>
                                 <text id="SvgjsText2745" font-family="Helvetica, Arial, sans-serif" x="314.08984375" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2746">May</tspan>
                                    <title>May</title>
                                 </text>
                                 <text id="SvgjsText2748" font-family="Helvetica, Arial, sans-serif" x="392.6123046875" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2749">Jun</tspan>
                                    <title>Jun</title>
                                 </text>
                                 <text id="SvgjsText2751" font-family="Helvetica, Arial, sans-serif" x="471.134765625" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2752">Jul</tspan>
                                    <title>Jul</title>
                                 </text>
                                 <text id="SvgjsText2754" font-family="Helvetica, Arial, sans-serif" x="549.6572265625" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2755">Aug</tspan>
                                    <title>Aug</title>
                                 </text>
                                 <text id="SvgjsText2757" font-family="Helvetica, Arial, sans-serif" x="628.1796875" y="248.112" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                    <tspan id="SvgjsTspan2758">Sep</tspan>
                                    <title>Sep</title>
                                 </text>
                              </g>
                           </g>
                           <g id="SvgjsG2786" class="apexcharts-yaxis-annotations"></g>
                           <g id="SvgjsG2787" class="apexcharts-xaxis-annotations"></g>
                           <g id="SvgjsG2788" class="apexcharts-point-annotations"></g>
                           <rect id="SvgjsRect2791" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect>
                           <rect id="SvgjsRect2792" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect>
                        </g>
                     </svg>
                     <div class="apexcharts-tooltip apexcharts-theme-light">
                        <div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                        <div class="apexcharts-tooltip-series-group" style="order: 1;">
                           <span class="apexcharts-tooltip-marker" style="background-color: rgb(22, 163, 74);"></span>
                           <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                              <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                              <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                              <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                           </div>
                        </div>
                        <div class="apexcharts-tooltip-series-group" style="order: 2;">
                           <span class="apexcharts-tooltip-marker" style="background-color: rgb(255, 159, 41);"></span>
                           <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                              <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                              <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                              <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                           </div>
                        </div>
                     </div>
                     <div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                        <div class="apexcharts-yaxistooltip-text"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xxl-4 col-lg-6">
      <div class="card h-100">
         <div class="card-body p-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
               <h6 class="text-lg mb-0">Top Teachers</h6>
               <div class="dropdown">
                  <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                  </button>
                  <ul class="dropdown-menu p-12 border bg-base shadow">
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                           <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                           View
                        </button>
                     </li>
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                           <iconify-icon icon="lucide:edit" class="icon text-lg line-height-1"></iconify-icon>
                           Edit
                        </button>
                     </li>
                     <li>
                        <button type="button" class="delete-item dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                           <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                           Delete
                        </button>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="ps-20 pt-20 pb-20">
               <div class="pe-20 d-flex flex-column gap-20 max-h-462-px overflow-y-auto scroll-sm">
                  <div class="d-flex align-items-center justify-content-between gap-16">
                     <div class="d-flex align-items-start gap-16">
                        <img src="assets/images/thumbs/top-teacher-img1.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        <div class="">
                           <h6 class="mb-0 text-lg">Theresa Webb</h6>
                           <span class="text-secondary-light text-sm mb-0">example@gmail.com</span>
                        </div>
                     </div>
                     <div class="text-end">
                        <span class="d-block fw-semibold text-primary-light">Mathematics</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between gap-16">
                     <div class="d-flex align-items-start gap-16">
                        <img src="assets/images/thumbs/top-teacher-img2.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        <div class="">
                           <h6 class="mb-0 text-lg">Darrell Steward</h6>
                           <span class="text-secondary-light text-sm mb-0">example@gmail.com</span>
                        </div>
                     </div>
                     <div class="text-end">
                        <span class="d-block fw-semibold text-primary-light">Physics</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between gap-16">
                     <div class="d-flex align-items-start gap-16">
                        <img src="assets/images/thumbs/top-teacher-img3.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        <div class="">
                           <h6 class="mb-0 text-lg">Jane Cooper</h6>
                           <span class="text-secondary-light text-sm mb-0">example@gmail.com</span>
                        </div>
                     </div>
                     <div class="text-end">
                        <span class="d-block fw-semibold text-primary-light">Biology</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between gap-16">
                     <div class="d-flex align-items-start gap-16">
                        <img src="assets/images/thumbs/top-teacher-img4.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        <div class="">
                           <h6 class="mb-0 text-lg">Savannah Nguyen</h6>
                           <span class="text-secondary-light text-sm mb-0">example@gmail.com</span>
                        </div>
                     </div>
                     <div class="text-end">
                        <span class="d-block fw-semibold text-primary-light">English</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between gap-16">
                     <div class="d-flex align-items-start gap-16">
                        <img src="assets/images/thumbs/top-teacher-img5.png" alt="Thumbnail" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        <div class="">
                           <h6 class="mb-0 text-lg">Eleanor Pena</h6>
                           <span class="text-secondary-light text-sm mb-0">example@gmail.com</span>
                        </div>
                     </div>
                     <div class="text-end">
                        <span class="d-block fw-semibold text-primary-light">Math</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xxl-4 col-lg-6">
      <div class="card h-100">
         <div class="card-body p-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between px-20 py-16 border-bottom border-neutral-200">
               <h6 class="text-lg mb-0">New Admissions</h6>
               <div class="dropdown">
                  <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                  </button>
                  <ul class="dropdown-menu p-12 border bg-base shadow">
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                           <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                           View
                        </button>
                     </li>
                     <li>
                        <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                           <iconify-icon icon="lucide:edit" class="icon text-lg line-height-1"></iconify-icon>
                           Edit
                        </button>
                     </li>
                     <li>
                        <button type="button" class="delete-item dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                           <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                           Delete
                        </button>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="p-20">
               <div class="position-relative text-center">
                  <div id="newAdmissions" class="y-value-left apexcharts-tooltip-z-none" style="min-height: 266.9px;">
                     <div id="apexchartsbrdddt53" class="apexcharts-canvas apexchartsbrdddt53 apexcharts-theme-light" style="width: 311px; height: 266.9px;">
                        <svg id="SvgjsSvg2793" width="311" height="266.9" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                           <foreignObject x="0" y="0" width="311" height="266.9">
                              <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"></div>
                           </foreignObject>
                           <g id="SvgjsG2795" class="apexcharts-inner apexcharts-graphical" transform="translate(20.5, 0)">
                              <defs id="SvgjsDefs2794">
                                 <clipPath id="gridRectMaskbrdddt53">
                                    <rect id="SvgjsRect2796" width="276" height="272" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                 </clipPath>
                                 <clipPath id="forecastMaskbrdddt53"></clipPath>
                                 <clipPath id="nonForecastMaskbrdddt53"></clipPath>
                                 <clipPath id="gridRectMarkerMaskbrdddt53">
                                    <rect id="SvgjsRect2797" width="274" height="274" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                 </clipPath>
                              </defs>
                              <g id="SvgjsG2798" class="apexcharts-pie">
                                 <g id="SvgjsG2799" transform="translate(0, 0) scale(1)">
                                    <circle id="SvgjsCircle2800" r="84.30975609756098" cx="135" cy="135" fill="transparent"></circle>
                                    <g id="SvgjsG2801" class="apexcharts-slices">
                                       <g id="SvgjsG2802" class="apexcharts-series apexcharts-pie-series" seriesName="Health" rel="1" data:realIndex="0">
                                          <path id="SvgjsPath2803" d="M 135 5.292682926829258 A 129.70731707317074 129.70731707317074 0 0 1 246.1999802953643 68.22760686757853 L 207.27998719198678 91.59794446392604 A 84.30975609756098 84.30975609756098 0 0 0 135 50.69024390243902 L 135 5.292682926829258 z" fill="rgba(10,81,206,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-0" index="0" j="0" data:angle="59.01639344262295" data:startAngle="0" data:strokeWidth="2" data:value="40" data:pathOrig="M 135 5.292682926829258 A 129.70731707317074 129.70731707317074 0 0 1 246.1999802953643 68.22760686757853 L 207.27998719198678 91.59794446392604 A 84.30975609756098 84.30975609756098 0 0 0 135 50.69024390243902 L 135 5.292682926829258 z" stroke="#ffffff"></path>
                                       </g>
                                       <g id="SvgjsG2804" class="apexcharts-series apexcharts-pie-series" seriesName="Business" rel="2" data:realIndex="1">
                                          <path id="SvgjsPath2805" d="M 246.1999802953643 68.22760686757853 A 129.70731707317074 129.70731707317074 0 0 1 118.34579383733275 263.6336873428241 L 124.17476599426628 218.61189677283568 A 84.30975609756098 84.30975609756098 0 0 0 207.27998719198678 91.59794446392604 L 246.1999802953643 68.22760686757853 z" fill="rgba(37,161,148,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-1" index="0" j="1" data:angle="128.36065573770492" data:startAngle="59.01639344262295" data:strokeWidth="2" data:value="87" data:pathOrig="M 246.1999802953643 68.22760686757853 A 129.70731707317074 129.70731707317074 0 0 1 118.34579383733275 263.6336873428241 L 124.17476599426628 218.61189677283568 A 84.30975609756098 84.30975609756098 0 0 0 207.27998719198678 91.59794446392604 L 246.1999802953643 68.22760686757853 z" stroke="#ffffff"></path>
                                       </g>
                                       <g id="SvgjsG2806" class="apexcharts-series apexcharts-pie-series" seriesName="Lifestyle" rel="3" data:realIndex="2">
                                          <path id="SvgjsPath2807" d="M 118.34579383733275 263.6336873428241 A 129.70731707317074 129.70731707317074 0 0 1 44.47153621835575 42.109821037659856 L 76.15649854193124 74.62138367447892 A 84.30975609756098 84.30975609756098 0 0 0 124.17476599426628 218.61189677283568 L 118.34579383733275 263.6336873428241 z" fill="rgba(255,122,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-2" index="0" j="2" data:angle="128.36065573770495" data:startAngle="187.37704918032787" data:strokeWidth="2" data:value="87" data:pathOrig="M 118.34579383733275 263.6336873428241 A 129.70731707317074 129.70731707317074 0 0 1 44.47153621835575 42.109821037659856 L 76.15649854193124 74.62138367447892 A 84.30975609756098 84.30975609756098 0 0 0 124.17476599426628 218.61189677283568 L 118.34579383733275 263.6336873428241 z" stroke="#ffffff"></path>
                                       </g>
                                       <g id="SvgjsG2808" class="apexcharts-series apexcharts-pie-series" seriesName="Entertainment" rel="4" data:realIndex="3">
                                          <path id="SvgjsPath2809" d="M 44.47153621835575 42.109821037659856 A 129.70731707317074 129.70731707317074 0 0 1 134.97736180264636 5.29268490238465 L 134.98528517172014 50.69024518655003 A 84.30975609756098 84.30975609756098 0 0 0 76.15649854193124 74.62138367447892 L 44.47153621835575 42.109821037659856 z" fill="rgba(0,159,94,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-3" index="0" j="3" data:angle="44.26229508196718" data:startAngle="315.7377049180328" data:strokeWidth="2" data:value="30" data:pathOrig="M 44.47153621835575 42.109821037659856 A 129.70731707317074 129.70731707317074 0 0 1 134.97736180264636 5.29268490238465 L 134.98528517172014 50.69024518655003 A 84.30975609756098 84.30975609756098 0 0 0 76.15649854193124 74.62138367447892 L 44.47153621835575 42.109821037659856 z" stroke="#ffffff"></path>
                                       </g>
                                    </g>
                                 </g>
                              </g>
                              <line id="SvgjsLine2810" x1="0" y1="0" x2="270" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                              <line id="SvgjsLine2811" x1="0" y1="0" x2="270" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                           </g>
                        </svg>
                        <div class="apexcharts-tooltip apexcharts-theme-dark">
                           <div class="apexcharts-tooltip-series-group" style="order: 1;">
                              <span class="apexcharts-tooltip-marker" style="background-color: rgb(10, 81, 206);"></span>
                              <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                 <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                 <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                 <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                              </div>
                           </div>
                           <div class="apexcharts-tooltip-series-group" style="order: 2;">
                              <span class="apexcharts-tooltip-marker" style="background-color: rgb(37, 161, 148);"></span>
                              <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                 <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                 <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                 <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                              </div>
                           </div>
                           <div class="apexcharts-tooltip-series-group" style="order: 3;">
                              <span class="apexcharts-tooltip-marker" style="background-color: rgb(255, 122, 44);"></span>
                              <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                 <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                 <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                 <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                              </div>
                           </div>
                           <div class="apexcharts-tooltip-series-group" style="order: 4;">
                              <span class="apexcharts-tooltip-marker" style="background-color: rgb(0, 159, 94);"></span>
                              <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                 <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                 <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                 <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="text-center position-absolute top-50 start-50 translate-middle">
                     <h5 class="mb-4">50</h5>
                     <span class="text-secondary-light">Total Admissions</span>
                  </div>
               </div>
               <ul class="d-flex flex-wrap align-items-center justify-content-center mt-48 gap-24">
                  <li class="d-flex align-items-center gap-2">
                     <span class="w-12-px h-12-px radius-2 bg-success-600 rotate-45-deg"></span>
                     <div class="">
                        <span class="text-secondary-light fw-medium">
                        English:
                        <span class="fw-bold text-primary-light">15</span>
                        </span>
                     </div>
                  </li>
                  <li class="d-flex align-items-center gap-2">
                     <span class="w-12-px h-12-px radius-2 bg-blue-600 rotate-45-deg"></span>
                     <div class="">
                        <span class="text-secondary-light fw-medium">
                        Math:
                        <span class="fw-bold text-primary-light">15</span>
                        </span>
                     </div>
                  </li>
                  <li class="d-flex align-items-center gap-2">
                     <span class="w-12-px h-12-px radius-2 bg-warning-600 rotate-45-deg"></span>
                     <div class="">
                        <span class="text-secondary-light fw-medium">
                        Biology:
                        <span class="fw-bold text-primary-light">5</span>
                        </span>
                     </div>
                  </li>
                  <li class="d-flex align-items-center gap-2">
                     <span class="w-12-px h-12-px radius-2 bg-primary-600 rotate-45-deg"></span>
                     <div class="">
                        <span class="text-secondary-light fw-medium">
                        Physics:
                        <span class="fw-bold text-primary-light">10</span>
                        </span>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xxl-4">
      <div class="card radius-12 border-0 h-100">
         <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between py-12 px-20 border-bottom border-neutral-200">
            <h6 class="mb-2 fw-bold text-lg">Top Student</h6>
            <div class="dropdown">
               <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
               </button>
               <ul class="dropdown-menu p-12 border bg-base shadow">
                  <li>
                     <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalView">
                        <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                        View
                     </button>
                  </li>
                  <li>
                     <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                        <iconify-icon icon="lucide:edit" class="icon text-lg line-height-1"></iconify-icon>
                        Edit
                     </button>
                  </li>
                  <li>
                     <button type="button" class="delete-item dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10" data-bs-toggle="modal" data-bs-target="#exampleModalDelete">
                        <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                        Delete
                     </button>
                  </li>
               </ul>
            </div>
         </div>
         <div class="card-body">
            <div class="d-flex flex-column gap-28">
               <div class="d-flex align-items-center justify-content-between gap-10">
                  <div class="d-flex align-items-center gap-12">
                     <span class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center">
                     <img src="assets/images/thumbs/avatar-img1.png" class="w-44-px h-44-px object-fit-cover rounded-circle" alt="Icon">
                     </span>
                     <div class="">
                        <h6 class="text-sm mb-2">Brooklyn Simmons</h6>
                        <span class="text-xs text-secondary-light">Class: Six</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center gap-8">
                     <span class="text-sm text-secondary-light">Marks</span>
                     <span class="text-primary-light text-sm d-block text-end">
                        <svg class="radial-progress w-44-px" data-percentage="20" viewBox="0 0 80 80">
                           <circle class="incomplete stroke-8-px opacity-02 stroke-blue" cx="40" cy="40" r="35"></circle>
                           <circle class="complete stroke-8-px stroke-blue" cx="40" cy="40" r="35">
                           </circle>
                           <text class="percentage fill-black" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">20</text>
                        </svg>
                     </span>
                  </div>
               </div>
               <div class="d-flex align-items-center justify-content-between gap-10">
                  <div class="d-flex align-items-center gap-12">
                     <span class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center">
                     <img src="assets/images/thumbs/avatar-img2.png" class="w-44-px h-44-px object-fit-cover rounded-circle" alt="Icon">
                     </span>
                     <div class="">
                        <h6 class="text-sm mb-2">Floyd Miles</h6>
                        <span class="text-xs text-secondary-light">Class: Seven</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center gap-8">
                     <span class="text-sm text-secondary-light">Marks</span>
                     <span class="text-primary-light text-sm d-block text-end">
                        <svg class="radial-progress w-44-px" data-percentage="35" viewBox="0 0 80 80">
                           <circle class="incomplete stroke-8-px opacity-02 stroke-red" cx="40" cy="40" r="35"></circle>
                           <circle class="complete stroke-8-px stroke-red" cx="40" cy="40" r="35">
                           </circle>
                           <text class="percentage fill-black" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">35</text>
                        </svg>
                     </span>
                  </div>
               </div>
               <div class="d-flex align-items-center justify-content-between gap-10">
                  <div class="d-flex align-items-center gap-12">
                     <span class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center">
                     <img src="assets/images/thumbs/avatar-img2.png" class="w-44-px h-44-px object-fit-cover rounded-circle" alt="Icon">
                     </span>
                     <div class="">
                        <h6 class="text-sm mb-2">Courtney Henry</h6>
                        <span class="text-xs text-secondary-light">Class: Eight</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center gap-8">
                     <span class="text-sm text-secondary-light">Marks</span>
                     <span class="text-primary-light text-sm d-block text-end">
                        <svg class="radial-progress w-44-px" data-percentage="45" viewBox="0 0 80 80">
                           <circle class="incomplete stroke-8-px opacity-02 stroke-warning" cx="40" cy="40" r="35">
                           </circle>
                           <circle class="complete stroke-8-px stroke-warning" cx="40" cy="40" r="35">
                           </circle>
                           <text class="percentage fill-black" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">45</text>
                        </svg>
                     </span>
                  </div>
               </div>
               <div class="d-flex align-items-center justify-content-between gap-10">
                  <div class="d-flex align-items-center gap-12">
                     <span class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center">
                     <img src="assets/images/thumbs/avatar-img4.png" class="w-44-px h-44-px object-fit-cover rounded-circle" alt="Icon">
                     </span>
                     <div class="">
                        <h6 class="text-sm mb-2">Kathryn Murphy</h6>
                        <span class="text-xs text-secondary-light">Class: Nine</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center gap-8">
                     <span class="text-sm text-secondary-light">Marks</span>
                     <span class="text-primary-light text-sm d-block text-end">
                        <svg class="radial-progress w-44-px" data-percentage="65" viewBox="0 0 80 80">
                           <circle class="incomplete stroke-8-px opacity-02 stroke-green" cx="40" cy="40" r="35"></circle>
                           <circle class="complete stroke-8-px stroke-green" cx="40" cy="40" r="35">
                           </circle>
                           <text class="percentage fill-black" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">65</text>
                        </svg>
                     </span>
                  </div>
               </div>
               <div class="d-flex align-items-center justify-content-between gap-10">
                  <div class="d-flex align-items-center gap-12">
                     <span class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center">
                     <img src="assets/images/thumbs/avatar-img5.png" class="w-44-px h-44-px object-fit-cover rounded-circle" alt="Icon">
                     </span>
                     <div class="">
                        <h6 class="text-sm mb-2">Annette Black</h6>
                        <span class="text-xs text-secondary-light">Class: Ten</span>
                     </div>
                  </div>
                  <div class="d-flex align-items-center gap-8">
                     <span class="text-sm text-secondary-light">Marks</span>
                     <span class="text-primary-light text-sm d-block text-end">
                        <svg class="radial-progress w-44-px" data-percentage="65" viewBox="0 0 80 80">
                           <circle class="incomplete stroke-8-px opacity-02 stroke-blue" cx="40" cy="40" r="35"></circle>
                           <circle class="complete stroke-8-px stroke-blue" cx="40" cy="40" r="35">
                           </circle>
                           <text class="percentage fill-black" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">65</text>
                        </svg>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
@endsection