class AppointmentManager {
    constructor(config) {
        this.container = document.querySelector(config.containerSelector);
        this.loader = document.querySelector(config.loaderSelector);
        this.dateInput = document.querySelector(config.dateSelector);
        this.prevBtn = document.querySelector(config.prevBtnSelector);
        this.nextBtn = document.querySelector(config.nextBtnSelector);
        this.clinicSelect = document.querySelector(config.clinicSelector);
        this.patientSelect = document.querySelector(config.patientSelector);
        this.loadUrl = config.loadUrl;
        this.updateSlotUrl = config.updateSlotUrl;
        this.storeHospitalAppointment = config.storeHospitalAppointment;
        this.statusAppointment = config.statusAppointment;
        this.destroyAppointment = config.destroyAppointment;  
        this.fetchAppointmentRoute = config.fetchAppointmentRoute; // <--- Add this
        this.csrfToken = config.csrfToken;
        this.patientId = config.patientId;
        this.selectedDate = config.initialDate;
        this.selectedClinic = this.clinicSelect.value || null;
        this.selectedPatient = this.patientSelect.value || null;
        this.slotDuration = window.currentSlotDuration || 15;
      
        
        this.draggedRow = null;

        this.init();
    }

    init() {
        // --- NEW CODE: Auto set values from URL ---
        const params = new URLSearchParams(window.location.search);
    
        const dateFromUrl = params.get("date");
        const clinicFromUrl = params.get("clinic_id");
        const patientFromUrl = params.get("patient_id");
    
        // Set DATE
        if (dateFromUrl) {
            this.selectedDate = dateFromUrl;
            if (this.dateInput) this.dateInput.value = dateFromUrl;
        }
    
        // Set CLINIC
        if (clinicFromUrl) {
            this.selectedClinic = clinicFromUrl;
            if (this.clinicSelect) this.clinicSelect.value = clinicFromUrl;
        } else {
            // Default first option
            this.selectedClinic = this.clinicSelect?.value || "";
        }
    
        // Set PATIENT
        if (patientFromUrl) {
            this.selectedPatient = patientFromUrl;
            if (this.patientSelect) this.patientSelect.value = patientFromUrl;
        }
    
        // ------------------------------------------
    
        this.loadAppointments();
    
    

        // Date navigation
        this.prevBtn?.addEventListener('click', () => this.changeDate(-1));
        this.nextBtn?.addEventListener('click', () => this.changeDate(1));
        this.dateInput?.addEventListener('change', () => {
            this.selectedDate = this.dateInput.value;
            this.loadAppointments();
        });

        // Clinic & patient filter
        this.clinicSelect?.addEventListener('change', () => {
            this.selectedClinic = this.clinicSelect.value;
            this.loadAppointments();
        });
        this.patientSelect?.addEventListener('change', () => {
            this.selectedPatient = this.patientSelect.value;
            this.loadAppointments();
        });

        // Full day report
        const reportBtn = document.querySelector('#fullDayReportBtn');
        reportBtn?.addEventListener('click', () => {
            if (!this.selectedDate) return alert("Select a date first");
            window.open(`${window.appConfig.reportUrl}?date=${this.selectedDate}`, '_blank');
        });


        // Delegate edit buttons
        document.addEventListener('click', e => {
            if(e.target.closest('.edit-appointment')){
                const btn = e.target.closest('.edit-appointment');
                const appointmentId = btn.dataset.id;
                this.fetchAppointmentData(appointmentId);
            }
        });

        // Delegate edit buttons
        document.addEventListener('click', e => {
            if(e.target.closest('.edit-hospital-appointment')){
                const btn = e.target.closest('.edit-hospital-appointment');
                const appointmentId = btn.dataset.id;
                this.fetchHospitalAppointmentData(appointmentId);
            }
        });
    }

    async loadAppointments() {
        if (!this.selectedDate) return;

        this.showLoader(true);

        const res = await fetch(this.loadUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({
                clinic_id: this.selectedClinic,
                patient_id: this.selectedPatient,
                date: this.selectedDate
            })
        });

        const data = await res.json();
        this.container.innerHTML = data.html || '<p>No appointments for this day.</p>';

        this.addDragDropHandlers();
        this.showLoader(false);
    }

    changeDate(offset) {
        const date = new Date(this.selectedDate);
        date.setDate(date.getDate() + offset);
        this.selectedDate = date.toISOString().split('T')[0];
        this.dateInput.value = this.selectedDate;
        this.loadAppointments();
    }

    showLoader(show) {
        this.loader.style.display = show ? 'block' : 'none';
    }

    addDragDropHandlers() {
        this.container.querySelectorAll('.draggable').forEach(row => {
            row.setAttribute('draggable', true);
            row.addEventListener('dragstart', (e) => this.onDragStart(e));
            row.addEventListener('dragover', (e) => this.onDragOver(e));
            row.addEventListener('drop', (e) => this.onDrop(e));
        });
    }

    async onDragStart(event) {
        this.draggedRow = event.currentTarget;
        event.dataTransfer.effectAllowed = "move";
        event.dataTransfer.setData("text/plain", this.draggedRow.dataset.appointmentId);
    }

    async onDragOver(event) {
        event.preventDefault();
    }

    async onDrop(event) {
        event.preventDefault();

        const targetRow = event.currentTarget;
        const targetTimeSlot = targetRow.dataset.timeSlot;
        const appointmentId = this.draggedRow.dataset.appointmentId;

        if (!appointmentId || !targetTimeSlot) return;

        targetRow.parentNode.insertBefore(this.draggedRow, targetRow.nextSibling);
        this.saveAppointmentSlotChange(appointmentId, targetTimeSlot);
    }

    async saveAppointmentSlotChange(appointmentId, newTime) {
        try {
            const res = await fetch(this.updateSlotUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ appointment_id: appointmentId, new_time: newTime })
            });
            const data = await res.json();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Appointment updated!',
                    timer: 2000,
                    showConfirmButton: false
                });
                this.loadAppointments();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to update appointment.',
                    timer: 2500,
                    showConfirmButton: false
                });
                this.loadAppointments();
            }
            
        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating appointment.',
                timer: 2500,
                showConfirmButton: false
            });
        }
    }

    async bookSlot(startTime) {
        const endTime = await this.addMinutesToTime(startTime, 15); // Default 15 min slot
        // Fill modal inputs

        document.getElementById('modal-dob').value = window.appConfig.patientDob || '';
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
        document.getElementById('modal-appointment-date').value = this.selectedDate;
        document.getElementById('appointment-clinic-id').value = this.selectedClinic;
        document.getElementById('patient_need').value = '';
        document.getElementById('appointment_type').value = '';
        document.getElementById('appointment_note').value = '';
       
        // Initialize select2 inside modal
        $('#patient-id').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#bookAppointmentModal')
        });

        // Auto-select patient if coming from URL or previous selection
        if (this.patientId) {
            $('#patient-id').val(this.patientId).trigger('change');
        }

        // Update DOB + Consultant when patient changes
        $('#patient-id').on('change', function () {
            const selectedOption = $(this).find(':selected');
            const dob = selectedOption.data('dob') || '';
            const consultant = selectedOption.data('consultant') || '';

            $('#modal-dob').val(dob);
            $('#clinic_consultant').val(consultant);
        });

        const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
        modal.show();

        this.setupSlotChangeHandler(startTime);
    }

    async openManualBookingModal() {
        const form = document.getElementById('manualBookingForm');
        form.reset();
        // Initialize select2 inside modal
        $('#hospital-patient-id').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#manualBookingModal')
        });

        // Auto-select patient if coming from URL or previous selection
        if (this.patientId) {
            $('#hospital-patient-id').val(this.patientId).trigger('change');
        }

        // Update DOB + Consultant when patient changes
        $('#hospital-patient-id').on('change', function () {
            const selectedOption = $(this).find(':selected');
            const dob = selectedOption.data('dob') || '';
            const consultant = selectedOption.data('consultant') || '';

            $('#hospital-dob').val(dob);
            $('#consultant').val(consultant);
        });
        
        document.getElementById('hospital-clinic-id').value = this.selectedClinic;
        document.getElementById('hospital_appointment_date').value = this.selectedDate;
        document.getElementById('admission_date').value = this.selectedDate;
        $('#procedure_id').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#manualBookingModal')  // important for modals!
        });
        let finalUrl = this.storeHospitalAppointment;
        $('#manualBookingForm').attr('data-action', finalUrl);

        const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
        modal.show();
    }

    async setupSlotChangeHandler(startTime) {
        const radios = document.querySelectorAll('.apt-slot-radio');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedSlots = parseInt(this.value);
                const endTime = this.addMinutesToTime(startTime, selectedSlots * this.slotDuration);
                document.getElementById('end_time').value = endTime;
            });
        });
    }

    async fetchAppointmentData(appointmentId) {
        try {
            const url = this.fetchAppointmentRoute.replace('__ID__', appointmentId);

            const res = await fetch(url);
            const data = await res.json();
            this.openModal(data);
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fix the errors in the form!',
                timer: 2500,
                showConfirmButton: false
            });
            console.error(err);
        }
    }

    openModal(data) {
        const modalEl = document.getElementById('bookAppointmentModal');
        const modal = new bootstrap.Modal(modalEl);

        document.getElementById('appointment-id').value = data.id || '';
        document.getElementById('modal-appointment-date').value = data.date || this.selectedDate;
        document.getElementById('start_time').value = data.start_time || '';
        document.getElementById('end_time').value = data.end_time || '';
        document.getElementById('appointment_note').value = data.appointment_note || '';
        document.getElementById('patient_need').value = data.patient_need || '';
        document.getElementById('appointment_type').value = data.appointment_type || '';
        document.getElementById('appointment-clinic-id').value = data.clinic_id || this.selectedClinic;

        $('#patient-id').val(data.patient_id || '').trigger('change');

        $('#patient-id').on('change', function () {
            const selectedOption = $(this).find(':selected');
            $('#modal-dob').val(selectedOption.data('dob') || '');
            $('#clinic_consultant').val(selectedOption.data('consultant') || '');
        });

        // Set DOB & Consultant immediately if editing
        const patientOption = $('#patient-id').find(`option[value="${data.patient_id}"]`);
        $('#modal-dob').val(patientOption.data('dob') || '');
        $('#clinic_consultant').val(patientOption.data('consultant') || '');

        modal.show();
    }

    async fetchHospitalAppointmentData(appointmentId) {
        try {
            const url = window.appConfig.fetchAppointmentRoute.replace('__ID__', appointmentId);
    
            const res = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });
    
            if (!res.ok) throw new Error('Network response was not ok');
    
            const data = await res.json();
            this.openHospitalModal(data);
        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load hospital appointment data',
                timer: 2500,
                showConfirmButton: false
            });
        }
    }
     
    openHospitalModal(data) {
        const modalEl = document.getElementById('manualBookingModal'); // your hospital modal
        const modal = new bootstrap.Modal(modalEl);
    
        // Set form values
        document.getElementById('hospital-appointment-id').value = data.id || '';
        document.getElementById('hospital_appointment_date').value = data.appointment_date || '';
        document.getElementById('hospital_start_time').value = data.start_time || '';
        document.getElementById('admission_date').value = data.admission_date || '';
        document.getElementById('admission_time').value = data.admission_time || '';
        document.getElementById('notes').value = data.appointment_note || '';
        document.getElementById('operation_duration').value = data.operation_duration || '';
        document.getElementById('ward').value = data.ward || '';
        document.getElementById('allergy').value = data.allergy || '';
        document.getElementById('hospital-clinic-id').value = data.clinic_id || '';
        document.getElementById('procedure_id').value = data.procedure_id || '';
    
        // Set patient select2
        $('#hospital-patient-id').val(data.patient_id || '').trigger('change');
    
        // Set consultant & DOB from patient data
        const selectedOption = $('#hospital-patient-id').find(`option[value="${data.patient_id}"]`);
        $('#hospital-dob').val(selectedOption.data('dob') || '');
        $('#consultant').val(selectedOption.data('consultant') || '');
        let finalUrl = this.storeHospitalAppointment;
        $('#manualBookingForm').attr('data-action', finalUrl);
        modal.show();
    }
    
    async openStatusModal(appointmentId, patientId, currentStatus) {
        // Fill hidden/input fields in modal
        $('#appointment_id').val(appointmentId);
        $('#patient_id').val(patientId);
        $('#appointment_status').val(currentStatus);
    
        // Generate the form action dynamically
        const finalUrl = this.statusAppointment(appointmentId, patientId);
        $('#statusChangeForm').attr('data-action', finalUrl);
    
        // Show the modal
        $('#statusChangeModal').modal('show');
    }

    async deleteAppointment(appointmentId, patientId, flag) {
        const url = this.destroyAppointment(appointmentId, patientId);
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the appointment.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            if (flag == 1) {
                                location.reload();
                            } else {
                                appointmentManager.loadAppointments();
                            }
                        } else {
                            Swal.fire('Error', data.message || 'Failed to delete.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    });
            }
        });
    };
    
    

    addMinutesToTime(time, minsToAdd) {
        let [h, m] = time.split(':').map(Number);
        m += minsToAdd;
        if (m >= 60) {
            h += Math.floor(m / 60);
            m = m % 60;
        }
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const appointmentManager = new AppointmentManager({
        containerSelector: '#appointments-container',
        loaderSelector: '#globalLoader',
        dateSelector: '#selectedDate',
        prevBtnSelector: '#prevDay',
        nextBtnSelector: '#nextDay',
        clinicSelector: '#clinic-select',
        patientSelector: '#patient-select',
        loadUrl: window.appConfig.loadAppointmentsUrl,
        fetchAppointmentRoute: window.appConfig.fetchAppointmentRoute,
        updateSlotUrl: window.appConfig.updateSlotUrl,
        csrfToken: window.appConfig.csrfToken,
        patientId: window.appConfig.patientId,
        initialDate: window.appConfig.initialDate,
        storeHospitalAppointment: window.appConfig.storeHospitalAppointment,
        statusAppointment: window.appConfig.statusAppointment,
        destroyAppointment: window.appConfig.destroyAppointment,
    });

    // Expose globally for onclick
    
    window.onDragStart = (event) => appointmentManager.onDragStart(event);
    window.onDragOver = (event) => appointmentManager.onDragOver(event);
    window.onDrop = (event) => appointmentManager.onDrop(event);
    window.bookSlot = (time) => appointmentManager.bookSlot(time);
    window.openManualBookingModal = () => appointmentManager.openManualBookingModal();
    window.openStatusModal = (appointmentId, patientId, status) => appointmentManager.openStatusModal(appointmentId, patientId, status);
    window.deleteAppointment= (appointmentId, patientId, flag) => appointmentManager.deleteAppointment(appointmentId, patientId, flag);
   // For Book Appointment Modal
    PopupForm.init('#bookAppointmentModal', '#bookAppointmentForm', (response) => {
        // Reload appointments after booking
        appointmentManager.loadAppointments();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.message || 'Appointment booked successfully!',
            timer: 2000,
            showConfirmButton: false
        });
    });

    // For Manual Booking Modal
    PopupForm.init('#manualBookingModal', '#manualBookingForm', (response) => {
        // Do something after manual booking
        appointmentManager.loadAppointments();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.message || 'Appointment booked For Hospital successfully!',
            timer: 2000,
            showConfirmButton: false
        });
    });

    PopupForm.init('#statusChangeModal', '#statusChangeForm', (response) => {
        // Optionally reload appointments table
        appointmentManager.loadAppointments();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.message || 'Status updated successfully!',
            timer: 2000,
            showConfirmButton: false
        });
        $('#statusChangeModal').modal('hide');
    });
    

});

