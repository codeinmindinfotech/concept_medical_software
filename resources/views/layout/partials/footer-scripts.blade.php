
		<!-- jQuery -->
        <script src="{{ URL::asset('/assets/js/jquery-3.7.1.min.js') }}"></script>
		

       
		<!-- Bootstrap Core JS -->
		<script src="{{ URL::asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
		
		<!-- Sticky Sidebar JS -->
        <script src="{{ URL::asset('/assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
        <script src="{{ URL::asset('/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>

		<!-- select JS -->
		<script src="{{ URL::asset('/assets/plugins/select2/js/select2.min.js') }}"></script>

		<!-- Owl Carousel JS -->
		<script src="{{ URL::asset('/assets/js/owl.carousel.min.js') }}"></script>

		<!-- Apexchart JS -->
		<script src="{{ URL::asset('/assets/plugins/apex/apexcharts.min.js') }}"></script>
		<script src="{{ URL::asset('/assets/plugins/apex/chart-data.js') }}"></script>

		<!-- Datepicker JS -->
		<script src="{{ URL::asset('/assets/js/moment.min.js') }}"></script>
		<script src="{{ URL::asset('/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

		<!-- Circle Progress JS -->
		<script src="{{ URL::asset('/assets/js/circle-progress.min.js') }}"></script>
		
        <!-- sweetalert2 JS -->
		<script src="{{ URL::asset('/assets/plugins/sweetalert/sweetalert2@11.js') }}"></script>
                {{-- <!-- Full Calendar JS -->
                <script src="{{ URL::asset('/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
                <script src="{{ URL::asset('/assets/js/fullcalendar.min.js') }}"></script>
                <script src="{{ URL::asset('/assets/js/jquery.fullcalendar.js') }}"></script> --}}
        
                
		<!-- Custom JS -->
		<script src="{{ URL::asset('/assets/js/script.js') }}"></script>
		
        @stack('scripts')
@php
 $guards = ['doctor', 'patient', 'clinic', 'web'];
 $user = null;
 $currentGuard = null;

 foreach ($guards as $guard) {
     if (auth($guard)->check()) {
         $user = auth($guard)->user();
         $currentGuard = $guard;
         break;
     }
 }
@endphp

@if ($user)
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
    window.NotificationConfig = {
    pusherKey: "{{ config('broadcasting.connections.pusher.key') }}",
    pusherCluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
    csrfToken: "{{ csrf_token() }}",
    channelName: "private-{{ strtolower(class_basename(auth()->user())) }}.{{ auth()->id() }}",
    markReadUrl: "{{ guard_route('notifications.markRead') }}",
    unreadUrl: "{{ guard_route('notifications.unread') }}"
    };
    </script>
    <script>
    const defaultAvatar = "{{ URL::asset('/assets_admin/img/doctors/doctor-thumb-01.jpg') }}";
    </script>
    <script src="{{ URL::asset('/assets/js/notification.js') }}"></script>
@endif