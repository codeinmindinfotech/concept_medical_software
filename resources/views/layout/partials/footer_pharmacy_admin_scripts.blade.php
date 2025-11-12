 <!-- jQuery -->
 <script src="{{ url('/assets_pharmacy/js/jquery-3.7.1.min.js') }}"></script>

 <!-- Slimscroll JS -->
 <script src="{{ url('/assets_pharmacy/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
 @if (Route::is(['pagee', 'pagees']))
     <script src="{{ url('/assets_pharmacy/plugins/raphael/raphael.min.js') }}"></script>
     <script src="{{ url('/assets_pharmacy/plugins/morris/morris.min.js') }}"></script>
     <script src="{{ url('/assets_pharmacy/js/chart.morris.js') }}"></script>
 @endif
 <!-- Bootstrap Core JS -->
 <script src="{{ URL::asset('/assets_pharmacy/js/bootstrap.bundle.min.js') }}"></script>
 <!-- Form Validation JS -->
 <script src="{{ url('/assets_pharmacy/js/form-validation.js') }}"></script>
 <!-- Mask JS -->
 <script src="{{ url('/assets_pharmacy/js/jquery.maskedinput.min.js') }}"></script>
 <script src="{{ url('/assets_pharmacy/js/mask.js') }}"></script>
 <!-- Select2 JS -->
 <script src="{{ url('/assets_pharmacy/js/select2.min.js') }}"></script>

 <!-- Datetimepicker JS -->
 <script src="{{ url('/assets_pharmacy/js/moment.min.js') }}"></script>
 <script src="{{ url('/assets_pharmacy/js/bootstrap-datetimepicker.min.js') }}"></script>

 <!-- Full Calendar JS -->
 @if (Route::is(['calendar']))
     <script src="{{ url('/assets_pharmacy/js/jquery-ui.min.js') }}"></script>
 @endif

 <script src="{{ url('/assets_pharmacy/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
 <script src="{{ url('/assets_pharmacy/plugins/fullcalendar/jquery.fullcalendar.js') }}"></script>
 <!-- Datatables JS -->
 <script src="{{ url('/assets_pharmacy/plugins/datatables/jquery.dataTables.min.js') }}"></script>
 <script src="{{ url('/assets_pharmacy/plugins/datatables/datatables.min.js') }}"></script>

 @if (Route::is(['supplier']))
     <script src="{{ URL::asset('/assets_pharmacy/js/supplier.js') }}"></script>
 @endif
 @if (Route::is(['categories']))
     <script src="{{ URL::asset('/assets_pharmacy/js/categories.js') }}"></script>
 @endif
 @if (Route::is(['customer-orders']))
     <script src="{{ URL::asset('/assets_pharmacy/js/customer-orders.js') }}"></script>
 @endif
 @if (Route::is(['expired']))
     <script src="{{ URL::asset('/assets_pharmacy/js/expired.js') }}"></script>
 @endif
 @if (Route::is(['invoice-report']))
     <script src="{{ URL::asset('/assets_pharmacy/js/invoice-report.js') }}"></script>
 @endif
 @if (Route::is(['order']))
     <script src="{{ URL::asset('/assets_pharmacy/js/order.js') }}"></script>
 @endif
 @if (Route::is(['outstock']))
     <script src="{{ URL::asset('/assets_pharmacy/js/outstock.js') }}"></script>
 @endif
 @if (Route::is(['products']))
     <script src="{{ URL::asset('/assets_pharmacy/js/products.js') }}"></script>
 @endif
 @if (Route::is(['product-list']))
     <script src="{{ URL::asset('/assets_pharmacy/js/product-list.js') }}"></script>
 @endif
 @if (Route::is(['purchase']))
     <script src="{{ URL::asset('/assets_pharmacy/js/purchase.js') }}"></script>
 @endif
 @if (Route::is(['sales']))
     <script src="{{ URL::asset('/assets_pharmacy/js/sales.js') }}"></script>
 @endif
 @if (Route::is(['transactions-list']))
     <script src="{{ URL::asset('/assets_pharmacy/js/transactions-list.js') }}"></script>
 @endif
 @if (Route::is(['pagees']))
     <script src="{{ URL::asset('/assets_pharmacy/js/index_pharmacy_admin.js') }}"></script>
 @endif

 <!-- Custom JS -->
 <script src="{{ url('/assets_pharmacy/js/script.js') }}"></script>
