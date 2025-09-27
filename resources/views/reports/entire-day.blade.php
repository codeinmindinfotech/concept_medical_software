<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Entire Day Report - {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</title>
    <style>
    @media print {
        footer {
            display: none !important;
        }
    }
    </style>
</head>
<body>
    <header style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px;">
    
        {{-- Left: Logo --}}
        <div style="flex: 0 0 auto;">
            <img src="{{ asset('theme/assets/img/logor.png') }}" alt="Clinic Logo" style="height: 60px;">
        </div>
    
        {{-- Center: Report Title --}}
        <div style="text-align: center; flex-grow: 1;">
            <h1 style="margin: 0; font-size: 24px;">Clinic Entire Day Report</h1>
            <div style="font-size: 16px; color: #555;">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</div>
        </div>
    
        {{-- Right: Empty or Optional Info --}}
        <div style="flex: 0 0 auto;">
            {{-- Optional: Clinic name, user name, etc. --}}
        </div>
    </header>
    

<main>
    @forelse ($clinics as $clinic)
        <h2 style="margin-top: 40px;">Clinic: {{ $clinic->name }}</h2>

        @if($clinic->appointments->isEmpty())
            <p><em>No appointments for this clinic on {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}.</em></p>
        @else
            <table width="100%" border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse; margin-bottom: 30px;">
                <thead style="background-color: #f1f1f1;">
                    <tr>
                        <th>Patient Name</th>
                        <th>Appointment Time</th>
                        <th>Doctor</th>
                        <th>Consultant</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clinic->appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            <td>{{ $appointment->patient->doctor->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->patient->consultant->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @empty
        <p><strong>No clinics found.</strong></p>
    @endforelse
</main>

@if (!isset($pdfExport) || !$pdfExport)
<footer>
    <button onclick="window.print()">Print</button>
    <button onclick="exportToPDF()">Export PDF</button>
    <button onclick="exportToExcel()">Export Excel</button>
    <button onclick="exportToWord()">Export Word</button>
    
    <hr>
    
    <input type="email" id="emailAddress" placeholder="Recipient email" />
    <label><input type="checkbox" id="attachPdf" /> Attach PDF</label>
    <button onclick="sendReportEmail()">Send Email</button>
</footer>
@endif
<script>
function exportToPDF() {
    // Simplest: just open print dialog to save as PDF
    window.print();
}

function exportToExcel() {
    // Basic export table to CSV/Excel
    const tableHtml = document.querySelector('main').innerHTML;
    const blob = new Blob([tableHtml], {type: 'application/vnd.ms-excel'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'entire_day_report_{{ $date }}.xls';
    link.click();
}

function exportToWord() {
    const header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
        "xmlns:w='urn:schemas-microsoft-com:office:word' " +
        "xmlns='http://www.w3.org/TR/REC-html40'>" +
        "<head><title>Entire Day Report</title></head><body>";
    const footer = "</body></html>";
    const sourceHTML = header + document.querySelector('main').innerHTML + footer;
    const blob = new Blob(['\ufeff', sourceHTML], {
        type: 'application/msword'
    });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'entire_day_report_{{ $date }}.doc';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function sendReportEmail() {
    const email = document.getElementById('emailAddress').value;
    const attachPdf = document.getElementById('attachPdf').checked;
    if (!email) {
        alert('Please enter a recipient email.');
        return;
    }

    fetch("{{ guard_route('reports.entire-day.email') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            date: '{{ $date }}',
            email: email,
            attach_pdf: attachPdf
        })
    })
    .then(response => response.json())
    .then(data => alert(data.message))
    .catch(() => alert('Failed to send email.'));
}
</script>

</body>
</html>
