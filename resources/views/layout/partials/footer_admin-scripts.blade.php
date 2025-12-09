 <!-- jQuery -->
 <script src="{{ URL::asset('/assets_admin/js/jquery-3.7.1.min.js') }}"></script>
 <!-- Slimscroll JS -->
 <script src="{{ URL::asset('/assets_admin/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

 <!-- Bootstrap Core JS -->
 <script src="{{ URL::asset('/assets_admin/js/bootstrap.bundle.min.js') }}"></script>

 <!-- Form Validation JS -->
 <script src="{{ URL::asset('/assets_admin/js/form-validation.js') }}"></script>
 <!-- Mask JS -->
 <script src="{{ URL::asset('/assets_admin/js/jquery.maskedinput.min.js') }}"></script>
 <script src="{{ URL::asset('/assets_admin/js/mask.js') }}"></script>
 <!-- Select2 JS -->
 {{-- <script src="{{ URL::asset('/assets_admin/js/select2.min.js') }}"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

 <!-- alert box -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <!-- Datetimepicker JS -->
 <script src="{{ URL::asset('/assets/js/moment.min.js') }}"></script>
 <script src="{{ URL::asset('/assets/js/bootstrap-datetimepicker.min.js') }}"></script>
 
 <!-- Full Calendar JS -->
 <script src="{{ URL::asset('/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
 
 <!-- Datatables JS -->
 <script src="{{ URL::asset('/assets_admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
 <script src="{{ URL::asset('/assets_admin/plugins/datatables/datatables.min.js') }}"></script>

 @stack('scripts')
 
<!-- Custom JS -->
<script src="{{ URL::asset('/assets_admin/js/script.js') }}"></script>
<script>
    // window.appConfig = {
    //      //set caledar days
    //     calendarDays: "{{ guard_route('calendar.days') }}",
	// 	savecalendarDays: "{{ guard_route('calendar.store') }}",
    // };
</script>
<!-- Modalpopup JS -->
{{-- <script src="{{ URL::asset('/assets/js/appointment.js') }}"></script> --}}
{{-- <script src="{{ URL::asset('/assets/js/modalpopup.js') }}"></script> --}}



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
    <script src="{{ URL::asset('/assets_admin/js/notification.js') }}"></script>
@endif