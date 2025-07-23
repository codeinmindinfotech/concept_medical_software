@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Patients', 'url' => route('patients.index')],
    ['label' => 'Recall Notification List'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Recall Notification List',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('patients.index'),
    'isListPage' => true
    ])

    @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
    @endsession
    @php
    $hasFilters = request()->hasAny(['from', 'to', 'phone', 'dob']);
    @endphp
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Recall Management
            </div>
            <div>
                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseSearch" aria-expanded="{{ $hasFilters ? 'true' : 'false' }}"
                    aria-controls="collapseSearch">
                    <i class="fas fa-filter me-1"></i> Advanced Search
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="accordion mb-4" id="searchAccordion">
                <div class="accordion-item border-0 shadow-sm">
                    <div id="collapseSearch" class="accordion-collapse {{ $hasFilters ? 'show' : '' }}"
                        aria-labelledby="headingSearch" data-bs-parent="#searchAccordion">
                        <div class="accordion-body">
                            <form method="GET" action="{{ route('recalls.notifications') }}">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="first_name" class="form-label"><strong>First Name</strong></label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            placeholder="e.g. John" value="{{ request('first_name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="surname" class="form-label"><strong>Surname</strong></label>
                                        <input type="text" name="surname" id="surname" class="form-control"
                                            placeholder="e.g. Doe" value="{{ request('surname') }}">
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <label for="pin" class="form-label">PIN</label>
                                        <input type="text" name="pin" id="pin" class="form-control"
                                            placeholder="e.g. 123456" value="{{ request('pin') }}">
                                    </div> --}}
                                    <div class="col-md-4">
                                        <label for="recall_filter" class="form-label"><strong>Interval<span
                                                    class="txt-error">*</span></strong></label>
                                        <select name="recall_filter" id="recall_filter" class="form-select select2">
                                            <option value="">-- Select --</option>
                                            <option value="month">This Month</option>
                                            <option value="6 weeks">6 weeks</option>
                                            <option value="2 months">2 months</option>
                                            <option value="3 months">3 months</option>
                                            <option value="6 months">6 months</option>
                                            <option value="1 year">1 year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="from" class="form-label"><strong>From<span
                                                    class="txt-error">*</span></strong></label>
                                        <div class="input-group">
                                            <input id="from" name="from" type="text" class="form-control flatpickr"
                                                placeholder="YYYY-MM-DD" value="{{ request('from') }}">
                                            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="to" class="form-label"><strong>To<span
                                                    class="txt-error">*</span></strong></label>
                                        <div class="input-group">
                                            <input id="to" name="to" type="text" class="form-control flatpickr"
                                                placeholder="YYYY-MM-DD" value="{{ request('to') }}">
                                            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i> Search
                                    </button>
                                    <a href="{{ route('recalls.notifications') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt me-1"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="recall-notification-list" data-pagination-container>
                <table class="table table-bordered" id="recallNotificationTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Patient</th>
                            <th>Recall Date</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($recalls->count())
                        @foreach($recalls as $recall)
                        <tr>
                            <td> {{ $recall->patient->first_name }} {{ $recall->patient->surname }} </td>
                            <td>{{ format_date($recall->recall_date) }}</td>
                            <td>{{ $recall->status->value }}</td>
                            <td>{{ $recall->note }}</td>
                            <td>
                                <a href="{{ route('patients.show', $recall->patient_id) }}"
                                    class="btn btn-info btn-sm">👤 View Patient</a>
                                {{-- <a href="{{ route('recalls.edit', $recall->id) }}"
                                    class="btn btn-warning btn-sm">📝 Edit Recall</a> --}}
                                <a href="{{ route('recalls.email', $recall->id) }}" class="btn btn-primary btn-sm">📧
                                    Email</a>
                                <a href="{{ route('recalls.sms', $recall->id) }}" class="btn btn-success btn-sm">📱
                                    SMS</a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                            <tr colspan="5">No recalls found for this month.</tr>
                        @endif 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
$('#recallNotificationTable').DataTable({
    paging: true,
    searching: false,
    ordering: true,
    info: true,
    lengthChange: true,
    pageLength: 10,
    columnDefs: [
        {
            targets: 4,  // Actions column - no sorting
            orderable: false
        }
    ],
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'print',
            text: '<i class="fa fa-print"></i> Print',
            className: 'btn btn-outline-secondary me-2',

            title: '',  // remove default title
            exportOptions: {
                columns: ':not(:last-child)'
            },
            customize: function (win) {
                // Add custom header to print view
                $(win.document.body).prepend(
                    '<h1 style="text-align:center; margin-bottom: 20px;">Recall Notification List</h1>' +
                    '<p style="text-align:center;">Generated on: ' + new Date().toLocaleDateString() + '</p>'
                );
                // Optional: style the table header
                $(win.document.body).find('table thead th').css({
                    'background-color': '#007bff',
                    'color': 'white',
                    'text-align': 'center'
                });
            }
        },
        {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel"></i> Excel',
            className: 'btn btn-outline-success me-2',
            title: 'Recall Notification List',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        },
        {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i> PDF',
            className: 'btn btn-outline-danger me-2',
            title: '', // remove default title for custom header
            pageSize: 'A4',
            orientation: 'landscape',
            exportOptions: {
                columns: ':not(:last-child)'
            },
            customize: function (doc) 
            {
                // Custom header and date
                doc.content.splice(0, 0, {
                    text: 'Recall Notification List',
                    style: 'header',
                    alignment: 'center',
                    margin: [0, 0, 0, 10]
                });
                doc.content.splice(1, 0, {
                    text: 'Generated on: ' + new Date().toLocaleDateString(),
                    style: 'subheader',
                    alignment: 'center',
                    margin: [0, 0, 0, 20]
                });

                // Equal column widths
                const columnCount = doc.content[doc.content.length - 1].table.body[0].length;
                doc.content[doc.content.length - 1].table.widths = Array(columnCount).fill('*');

                // Reduce default margins (top, left, right, bottom)
                doc.pageMargins = [40, 40, 40, 40]; // you can try smaller numbers like 20 or 30

                // Remove extra space below table if any by tweaking layout
                if (doc.content[doc.content.length - 1].table.layout === undefined) {
                    doc.content[doc.content.length - 1].table.layout = {};
                }

                // Remove padding below table rows (optional, adjust if needed)
                doc.content[doc.content.length - 1].table.layout.paddingBottom = 0;

                // Styles
                doc.styles = {
                    header: {
                        fontSize: 18,
                        bold: true
                    },
                    subheader: {
                        fontSize: 12,
                        italics: true
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 13,
                        color: 'white',
                        fillColor: '#007bff',
                        alignment: 'center'
                    }
                };
            }

        }
    ]
});


document.addEventListener("DOMContentLoaded", function () {
    // Initialize Select2
    $('#recall_filter').select2();

    const recallFilter = document.getElementById('recall_filter');
    const fromDateInput = document.getElementById('from');
    const toDateInput = document.getElementById('to');

    function setDateRange(interval) {
    const today = new Date();
    let fromDate = new Date(today);
    let toDate = new Date(today);

    switch (interval) {
        case 'month':
            // Start from 1st of current month
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
            // End of current month
            toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case '6 weeks':
            toDate.setDate(toDate.getDate() + 42);
            break;
        case '2 months':
            toDate.setMonth(toDate.getMonth() + 2);
            break;
        case '3 months':
            toDate.setMonth(toDate.getMonth() + 3);
            break;
        case '6 months':
            toDate.setMonth(toDate.getMonth() + 6);
            break;
        case '1 year':
            toDate.setFullYear(toDate.getFullYear() + 1);
            break;
        default:
            fromDateInput.value = '';
            toDateInput.value = '';
            return;
    }

    // Format to YYYY-MM-DD
    fromDateInput.value = fromDate.toISOString().split('T')[0];
    toDateInput.value = toDate.toISOString().split('T')[0];
}


    // Handle change event for Select2
    $('#recall_filter').on('change', function () {
        setDateRange(this.value);
    });

    // Set initial values if page is loaded with a recall_filter
    if (recallFilter.value) {
        setDateRange(recallFilter.value);
    }
});
</script>
@endpush