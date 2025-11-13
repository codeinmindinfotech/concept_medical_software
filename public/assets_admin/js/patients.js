    // Bootstrap tooltips setup
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Detect if table exists (avoid JS error on other tabs)
    if ($('#PatientTable').length) {
        const table = new DataTable('#PatientTable', {
            ajax: {
                url: '{{ route("patients.ajax") }}',
                data: {
                    tab: '{{ request("tab") }}' // send tab info to backend (Active/Trashed)
                },
                dataSrc: 'data'
            },
            columns: [
                { data: 'index' },
                { data: 'doctor' },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="d-flex align-items-center gap-2">
                                <img src="${data.patient_picture}" alt="Patient" class="rounded-circle" width="40" height="40">
                                ${data.patient_name}
                            </div>
                        `;
                    }
                },
                { data: 'address' },
                { data: 'phone' },
                { data: 'dob' },
                {
                    data: 'status',
                    render: function(data) {
                        return data === 'Trashed'
                            ? `<span class="badge bg-danger">${data}</span>`
                            : `<span class="badge bg-success">${data}</span>`;
                    }
                },
                @if(isset($trashed) && $trashed)
                {
                    data: null,
                    render: function(data) {
                        return `
                            <form action="/patients/${data.id}/restore" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>
                        `;
                    }
                }
                @endif
            ],
            paging: true,
            searching: true,
            order: [[0, 'asc']],
            layout: {
                topStart: null
            }
        });
    }
