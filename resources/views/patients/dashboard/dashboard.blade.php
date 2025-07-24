@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.index')],
            ['label' => 'Patients List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Patients List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.create'),
        'isListPage' => true
    ])
    <div class="row">
        
        <!-- Patient Dropdown -->
        <div class="mb-3">
            <label for="patientName" class="form-label"><strong>Select Patient :</strong></label>
            <select id="patientSelect" name="patient_id" class="select2 @error('patient_id') is-invalid @enderror">
                <option value="">-- Select Patient --</option>
                @foreach($patients as $patient)
                @php
                    $patientData = array(
                        "url" => route('tasks.tasks.index', ['patient' => $patient]),
                        "id" => $patient->id,
                        "name" => $patient->first_name . " " . $patient->surname,
                        "dob" => format_date($patient->dob),
                        "gender" => $patient->gender,
                        "doctor" => $patient->doctor->name ?? "N/A",
                        "address" => $patient->address
                    );
                @endphp
                <option
                    value="{{ $patient->id }}"
                    data-info='@json($patientData)'
                >
                    {{ $patient->first_name }} {{ $patient->surname }}
                </option>
                @endforeach

            </select>
        </div>

<!-- Display Card Area -->
<div id="patientCard" style="margin-top: 30px;"></div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
      $('#patientSelect').select2({
        placeholder: "Search for a patient",
        allowClear: true
      });
    
      $('#patientSelect').on('change', function () {
        const selected = $(this).find(':selected');
        const raw = selected.attr('data-info');
        let data;
    
        try {
          data = JSON.parse(raw);
        } catch (err) {
          console.error("JSON parsing error", err);
          $('#patientCard').html('<p style="color:red">Could not load patient info.</p>');
          return;
        }
    
        if (data) {
          showPatientCard(data);
        } else {
          $('#patientCard').html('');
        }
      });
    
      function showPatientCard(data) {
  const dashboardUrl = `/patients/${data.id}/dashboard`; // Adjust if needed

  const html = `
    <div style="
      max-width: 420px;
      padding: 25px 30px;
      border-radius: 12px;
      background: #f5f7fa;  /* Soft light gray-blue */
      color: #333;          /* Darker text for readability */
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1); /* soft shadow */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      transition: transform 0.25s ease;
      position: relative;
      border: 1px solid #d1d9e6; /* subtle border */
    " 
    onmouseover="this.style.transform='scale(1.02)'" 
    onmouseout="this.style.transform='scale(1)'"
    >
      <h2 style="margin-top:0; margin-bottom: 15px; font-weight: 700; letter-spacing: 1px; color: #1e2a38;">
        <i class="fas fa-user" style="color: #4a90e2; margin-right: 8px;"></i> ${data.name || 'N/A'}
      </h2>

      <p style="margin: 6px 0; font-size: 1rem;">
        <i class="fas fa-birthday-cake" style="margin-right:8px; color: #7a8ba6;"></i>
        <strong>Date Of Birth:</strong> ${data.dob || 'N/A'}
      </p>

      <p style="margin: 6px 0; font-size: 1rem;">
        <i class="fas fa-venus-mars" style="margin-right:8px; color: #7a8ba6;"></i>
        <strong>Gender:</strong> ${data.gender || 'N/A'}
      </p>

      <p style="margin: 6px 0; font-size: 1rem;">
        <i class="fas fa-user-md" style="margin-right:8px; color: #7a8ba6;"></i>
        <strong>Doctor:</strong> ${data.doctor || 'N/A'}
      </p>

      <p style="margin: 6px 0; font-size: 1rem;">
        <i class="fas fa-info-circle" style="margin-right:8px; color: #7a8ba6;"></i>
        <strong>Address:</strong> ${data.address || 'N/A'} </span>
      </p>

      <a href="${data.url}"
        class="btn btn-sm btn-dark"
        style="
          position: absolute;
          top: 20px;
          right: 20px;
          border-radius: 5px;
          padding: 6px 10px;
          display: flex;
          align-items: center;
          text-decoration: none;
          color: #fff;
          background-color: #4a90e2;
          border: none;
          box-shadow: 0 3px 6px rgba(74, 144, 226, 0.5);
          transition: background-color 0.3s ease;
        "
        onmouseover="this.style.backgroundColor='#357ABD'"
        onmouseout="this.style.backgroundColor='#4a90e2'"
        title="Dashboard"
      >
        <i class="fas fa-tachometer-alt"></i>
      </a>
    </div>
  `;

  $('#patientCard').html(html);
}

function statusColor(status) {
  switch ((status || '').toLowerCase()) {
    case 'admitted': return '#388e3c';    // dark green
    case 'icu': return '#d32f2f';         // dark red
    case 'discharged': return '#1976d2';  // dark blue
    default: return '#555';                // neutral dark grey
  }
}

    });
    </script>
    
@endpush
