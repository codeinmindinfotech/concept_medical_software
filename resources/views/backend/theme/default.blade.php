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
</head>
<body class="sb-nav-fixed">

    @include('backend.theme.header')

    <div id="layoutSidenav">

        @include('backend.theme.sidebar')
        <div id="layoutSidenav_content">

            <main>
                @yield('content')
            </main>

            @include('backend.theme.footer')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- alert box -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('theme/main/js/scripts.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('theme/main/js/datatables-simple-demo.js') }}"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

    <script src="{{ asset('theme/assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('theme/assets/demo/chart-bar-demo.js') }}"></script>
    @stack('scripts')
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
            // flatpickr("#datepicker", {
            //     dateFormat: "Y-m-d",
            //     maxDate: "today",
            //     allowInput: true,
            //     clickOpens: false // prevent auto open on input
            // });

            // document.getElementById('dobTrigger').addEventListener('click', function () {
            //     document.querySelector('#datepicker')._flatpickr.open();
            // });

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
</body>
</html>

