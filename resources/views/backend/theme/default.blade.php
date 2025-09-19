<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? 'Concept Medical Software' }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">


    <!-- Select2 Core + Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.2/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <link href="{{ asset('theme/css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('theme/css/select-2.css') }}" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>
<body class="sb-nav-fixed">

    @include('backend.theme.header')

    <div id="layoutSidenav">

        @include('backend.theme.sidebar')
        <div id="layoutSidenav_content">
        <!-- Global AJAX Loader -->
        <div id="globalLoader" style="display: none; position: fixed; top: 0; left: 0; 
            width: 100%; height: 100%; background-color: rgba(255,255,255,0.7); 
            z-index: 9999; text-align: center;">
            <div style="position: absolute; top: 50%; left: 50%; 
                        transform: translate(-50%, -50%);">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 fw-bold text-primary">Please wait...</p>
            </div>
        </div>

            <main>
                @yield('content')
            </main>

            @include('backend.theme.footer')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script src="{{ asset('theme/main/js/scripts.js') }}"></script>
     @stack('scripts')
    
 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- alert box -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Buttons extension JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- JSZip for Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- pdfmake for PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Buttons for Excel/PDF -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('theme/main/js/datatables-simple-demo.js') }}"></script> --}}
    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#insurance_id').select2({
                theme: 'bootstrap-5'
                , placeholder: $('#insurance_id').data('placeholder')
                , allowClear: true
                , width: '100%'
                , closeOnSelect: false // important for multiple select UX
            });
        });
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5'
                , placeholder: '-- Select --'
                , allowClear: true
                , width: '100%' // Ensures it spans full width
            }).on('select2:select select2:unselect', function() {
                toggleBorder($(this));
            });

            // Apply initial state on load
            toggleBorder($('.select2'));

            function toggleBorder($select) {
                const $selection = $select.next('.select2-container').find('.select2-selection');
                if ($select.val()) {
                    $selection.css({
                        'border': 'none'
                        , 'background-color': 'transparent'
                        , 'box-shadow': 'none'
                    });
                } else {
                    $selection.css({
                        'border': '1px solid #ced4da'
                        , 'background-color': '#fff'
                    });
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#datepicker", {
                dateFormat: "Y-m-d"
                , maxDate: "today", // only allow past dates
                allowInput: true, // also allow manual input
            });
        });

        function ajaxPaginate(containerSelector, url) {
            $.ajax({
                url: url
                , type: 'GET'
                , success: function(data) {
                    $(containerSelector).html(data);
                }
                , error: function(xhr) {
                    console.error("AJAX Pagination Error:", xhr.responseText);
                }
            });
        }

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const pageUrl = $(this).attr('href');

            // Find the closest container
            const container = $(this).closest('[data-pagination-container]').attr('id');
            if (!container) {
                console.warn("No container with data-pagination-container found.");
                return;
            }

            ajaxPaginate(`#${container}`, pageUrl);
        });

    </script>
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
        markReadUrl: "{{ guard_route('notifications.markRead') }}"
    };
</script>
<script src="{{ asset('theme/main/js/notification.js') }}"></script>
@endif
</body>
</html>

