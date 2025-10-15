@extends('backend.theme.default')

@section('content')
<style>
    #tab-nav {
        position: sticky;
        top: 20px;
        z-index: 1020;
        /* above content */
    }
</style>
<div class="container-fluid">
    <div class="row">
        {{-- Tab Content --}}
        <div class="col-12 col-md-10">
            <div class="tab-content" id="tab-content">
                @php
                $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
                ['label' => 'Patients', 'url' => guard_route('patients.index')],
                ['label' => 'Patients List'],
                ];
                @endphp

                @include('backend.theme.breadcrumb', [
                    'pageTitle' => 'Patients List',
                    'breadcrumbs' => $breadcrumbs,
                    'isListPage' => true
                ])

                @session('success')
                <div class="alert alert-success" role="alert">
                    {{ $value }}
                </div>
                @endsession
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-notes-medical me-1"></i>
                        Patient Appointment Management
                    </div>
                    <div class="card-body">
                        <div id="patient-apts-list" data-pagination-container>
                            @include('patients.apt.list', [
                            'patient' => $patient,
                            'apts'=> $apts
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Sidebar Tabs --}}
        <div class="col-12 col-md-2">
            <div class="nav flex-column nav-pills position-sticky" id="tab-nav" style="z-index: 1020;top: 20px;"
                role="tablist" aria-orientation="vertical">
                @include('backend.theme.tab-navigation', ['patient' => $patient])
            </div>
        </div>

        <x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''"
            :procedures="$procedures" :flag="1"
            :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

        <x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''"
            :appointmentTypes="$appointmentTypes" :flag="1"
            :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
<script src="{{ asset('theme/patient-diary.js') }}"></script>
<script>
    const routes = {
        storeAppointment: "{{ $patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal') }}",
        storeHospitalAppointment: "{{ $patient ?guard_route('hospital_appointments.store', ['patient' => $patient->id]) :guard_route('hospital_appointments.storeGlobal') }}",
    };

    $('#PatientApt').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        columnDefs: [
            {
                targets: 4, // Disable ordering for the 'Actions' column
                orderable: false
            }
        ]
    });

   document.addEventListener('click', function (e) {
        if (e.target.closest('.edit-appointment')) {
            const button = e.target.closest('.edit-appointment');

            const id = button.dataset.id;
            const type = button.dataset.type;
            const date = button.dataset.date;
            const start = button.dataset.start;
            const end = button.dataset.end;
            const need = button.dataset.need;
            const note = button.dataset.note;
            const patient_id = button.dataset.patient_id;
            const dob = button.dataset.dob;
            const consultant = button.dataset.consultant;
            const clinic_id = button.dataset.clinic_id;

            document.getElementById('bookAppointmentLabel').textContent = 'Edit Appointment';
            document.getElementById('modal-submit-btn').textContent = 'Update Appointment';

            document.getElementById('appointment-id').value = id;
            document.getElementById('appointment_type').value = type;
            document.getElementById('modal-appointment-date').value = date;
            document.getElementById('start_time').value = start;
            document.getElementById('end_time').value = end;
            document.getElementById('patient_need').value = need;
            document.getElementById('appointment_note').value = note;
            document.getElementById('clinic_consultant').value = consultant;
            document.getElementById('appointment-clinic-id').value = clinic_id;
            console.log(consultant);
            const patientName = "{{ $patient ? $patient->full_name : ""}}";

            const modalPatientNameInput = document.getElementById('modal-patient-name');
            if (modalPatientNameInput) {
                modalPatientNameInput.value = patientName;
            }

            document.getElementById('modal-dob').value = "{{ $patient ? format_date($patient->dob): '' }}";

            const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
            modal.show();
            
            if(patientName == ''){
                $('#patient-id').val(patient_id).trigger('change');
                $('#patient-id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#bookAppointmentModal')  // important for modals!
                });
            }  
        }

        if (e.target.closest('.edit-hospital-appointment')) {
            const button = e.target.closest('.edit-hospital-appointment');

            const id = button.dataset.id;
            const patient_id = button.dataset.patient_id;
            const date = button.dataset.date;
            const admission_date = button.dataset.admission_date;
            const start = button.dataset.start;
            const admission_time = button.dataset.admission_time;
            const need = button.dataset.need;
            const note = button.dataset.note;
            const procedure_id = button.dataset.procedure_id;
            const operation_duration = button.dataset.operation_duration;
            const ward = button.dataset.ward;
            const allergy = button.dataset.allergy;
            const clinic_id = button.dataset.clinic_id;
            const consultant = button.dataset.consultant;
            const action =  button.dataset.action; 

            document.getElementById('manualBookingLabel').textContent = 'Edit Appointment';
            document.getElementById('booking-submit-btn').textContent = 'Update Appointment';

            document.getElementById('hospital-appointment-id').value = id;
            document.getElementById('hospital_appointment_date').value = date;
            document.getElementById('hospital_start_time').value = start;
            document.getElementById('admission_time').value = admission_time;
            document.getElementById('admission_date').value = admission_date;
            document.getElementById('patient_need').value = need;
            document.getElementById('notes').value = note;
            document.getElementById('procedure_id').value = procedure_id;
            document.getElementById('operation_duration').value = operation_duration;
            document.getElementById('ward').value = ward;
            document.getElementById('allergy').value = allergy;
            document.getElementById('hospital-clinic-id').value = clinic_id;
            document.getElementById('consultant').value = consultant;
            document.getElementById('hospital-dob').value = "{{ $patient ? format_date($patient->dob): '' }}";
            
            $('#manualBookingForm').attr('data-action', action);

            const patientName = "{{ $patient ? $patient->full_name : ''}}";
            const modalPatientNameInput = document.getElementById('hospital-patient-name');
            if (modalPatientNameInput) {
                modalPatientNameInput.value = patientName;
            } 

            const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
            modal.show();
            
            $('#procedure_id').val(procedure_id).trigger('change');
                $('#procedure_id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#manualBookingModal') 
                });
            if(patientName == ''){
                $('#hospital-patient-id').val(patient_id).trigger('change');
                $('#hospital-patient-id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#manualBookingModal') 
                });
            }
        }   
    });
</script>
@endpush