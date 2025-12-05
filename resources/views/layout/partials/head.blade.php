

		<!-- Theme Settings Js -->
		<script src="{{ url('/assets/js/theme-script.js') }}"></script>
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ url('/assets/css/bootstrap.min.css') }}">
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{ url('/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
		<link rel="stylesheet" href="{{ url('/assets/plugins/fontawesome/css/all.min.css') }}">

		<!-- Iconsax CSS-->
		<link rel="stylesheet" href="{{ url('/assets/css/iconsax.css') }}">

		<!-- Feathericon CSS -->
    	<link rel="stylesheet" href="{{ url('/assets/css/feather.css') }}">

		<!-- Owl carousel CSS -->
		<link rel="stylesheet" href="{{ url('/assets/css/owl.carousel.min.css') }}">

		<!-- select CSS -->
		<link rel="stylesheet" href="{{ url('/assets/plugins/select2/css/select2.min.css') }}">

		<!-- Datepicker CSS -->
		<link rel="stylesheet" href="{{ url('/assets/css/bootstrap-datetimepicker.min.css') }}">

		<!-- Apex Css -->
		<link rel="stylesheet" href="{{ url('/assets/plugins/apex/apexcharts.css') }}">
		
		<!-- Full Calander CSS -->
		{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet"> --}}
		<link rel="stylesheet" href="{{ url('/assets/plugins/fullcalendar/3.10.2/fullcalendar.min.css') }}">

		{{-- <link rel="stylesheet" href="{{ url('assets/css/fullcalendar.min.css') }}"> --}}

		<!-- Main CSS -->
		<link rel="stylesheet" href="{{ url('/assets/css/custom.css') }}">

		<!-- Datatables CSS -->
		<link rel="stylesheet" href="{{ url('assets_admin/plugins/datatables/datatables.min.css') }}">
<style>/* ================================
	DATATABLE PAGINATION (DOC CURE STYLE)
	Compatible with DataTables v2
 =================================== */
 
 /* Pagination wrapper */
 .dt-paging {
	 margin-top: 20px;
 }
 
 .dt-paging nav {
	 display: flex;
	 align-items: center;
	 gap: 6px;
 }
 
 /* All pagination buttons */
 .dt-paging-button {
	 padding: 8px 14px !important;
	 background: #ffffff !important;
	 border: 1px solid #e4e4e4 !important;
	 color: #6c757d !important;
	 border-radius: 6px !important;
	 cursor: pointer !important;
	 font-size: 14px !important;
	 min-width: 38px;
	 text-align: center;
	 line-height: normal;
	 transition: all 0.25s ease;
 }
 
 /* Rounded like Doccure */
 .dt-paging-button {
	 border-radius: 50px !important;
 }
 
 /* Hover State */
 .dt-paging-button:not(.disabled):hover {
	 background: #eef3ff !important;
	 border: 2px solid #c7d3ff  !important;

	 border-color: #c7d3ff !important;
	 color: #336c9c !important;
 }
 
 /* Active Page */
 .dt-paging-button.current {
	 /* background: #336c9c !important; */
	 color: #ffffff !important;
	 border: 2px solid #336c9c  !important;
	 /* border-color: #336c9c !important; */
	 font-weight: 600 !important;
 }
 
 /* Disabled Buttons */
 .dt-paging-button.disabled {
	 background: #f8f9fa !important;
	 color: #b7b7b7 !important;
	 border-color: #e4e4e4 !important;
	 cursor: not-allowed !important;
	 opacity: 0.6;
 }
 
 /* First / Previous / Next / Last icons styling */
 .dt-paging-button.first,
 .dt-paging-button.previous,
 .dt-paging-button.next,
 .dt-paging-button.last {
	 font-weight: bold;
 }
 
 /* Prevent pagination from squashing */
 .dt-layout-end {
	 display: flex;
	 justify-content: flex-end;
 }
 
 /* ------- Mobile Responsive -------- */
 @media (max-width: 576px) {
 
	 .dt-paging nav {
		 flex-wrap: wrap;
		 gap: 4px;
	 }
 
	 .dt-paging-button {
		 padding: 6px 10px !important;
		 font-size: 13px !important;
		 min-width: 30px;
	 }
 }
 
 /* For Dark backgrounds (optional) */
 .dark-mode .dt-paging-button {
	 border-color: #444 !important;
	 background: #222 !important;
	 color: #bbb !important;
 }
 
</style>
		@stack('styles')