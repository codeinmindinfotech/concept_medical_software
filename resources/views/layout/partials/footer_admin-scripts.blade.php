 <!-- jQuery -->
 <script src="{{ URL::asset('/assets_admin/js/jquery-3.7.1.min.js') }}"></script>
 <!-- Slimscroll JS -->
 <script src="{{ URL::asset('/assets_admin/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
 @if (Route::is(['pagee']))
     <script src="{{ URL::asset('/assets_admin/plugins/raphael/raphael.min.js') }}"></script>
     <script src="{{ URL::asset('/assets_admin/plugins/morris/morris.min.js') }}"></script>
     <script src="{{ URL::asset('/assets_admin/js/chart.morris.js') }}"></script>
 @endif
 <!-- Bootstrap Core JS -->
 <script src="{{ URL::asset('/assets_admin/js/bootstrap.bundle.min.js') }}"></script>

 <!-- Form Validation JS -->
 <script src="{{ URL::asset('/assets_admin/js/form-validation.js') }}"></script>
 <!-- Mask JS -->
 <script src="{{ URL::asset('/assets_admin/js/jquery.maskedinput.min.js') }}"></script>
 <script src="{{ URL::asset('/assets_admin/js/mask.js') }}"></script>
 <!-- Select2 JS -->
 <script src="{{ URL::asset('/assets_admin/js/select2.min.js') }}"></script>

 <!-- Datetimepicker JS -->
 <script src="{{ URL::asset('/assets_admin/js/moment.min.js') }}"></script>
 <script src="{{ URL::asset('/assets_admin/js/bootstrap-datetimepicker.min.js') }}"></script>

 <!-- Full Calendar JS -->
 <script src="{{ URL::asset('/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
 <script src="{{ URL::asset('/assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
 <script src="{{ URL::asset('/assets/plugins/fullcalendar/jquery.fullcalendar.js') }}"></script>

 <!-- Datatables JS -->
 <script src="{{ URL::asset('/assets_admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
 <script src="{{ URL::asset('/assets_admin/plugins/datatables/datatables.min.js') }}"></script>

 @if (Route::is(['tables-basic']))
     <script src="{{ URL::asset('/assets_admin/js/tables-basic.js') }}"></script>
 @endif
 @if (Route::is(['data-tables']))
     <script src="{{ URL::asset('/assets_admin/js/data-tables.js') }}"></script>
 @endif
 @if (Route::is(['appointment-list', 'pagee']))
     <script src="{{ URL::asset('/assets_admin/js/appointment.js') }}"></script>
 @endif
 @if (Route::is(['doctor-list']))
     <script src="{{ URL::asset('/assets_admin/js/doctor.js') }}"></script>
 @endif
 @if (Route::is(['patient-list']))
     <script src="{{ URL::asset('/assets_admin/js/patients.js') }}"></script>
 @endif
 @if (Route::is(['invoice-report']))
     <script src="{{ URL::asset('/assets_admin/js/invoice-report.js') }}"></script>
 @endif
 @if (Route::is(['reviews']))
     <script src="{{ URL::asset('/assets_admin/js/review.js') }}"></script>
 @endif
 @if (Route::is(['specialities']))
     <script src="{{ URL::asset('/assets_admin/js/specialities.js') }}"></script>
 @endif
 @if (Route::is(['transactions-list']))
     <script src="{{ URL::asset('/assets_admin/js/transactions-list.js') }}"></script>
 @endif

 <!-- Custom JS -->
 <script src="{{ URL::asset('/assets_admin/js/script.js') }}"></script>
