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
        this.csrfToken = config.csrfToken;

        this.selectedDate = config.initialDate;
        this.selectedClinic = this.clinicSelect.value || null;
        this.selectedPatient = this.patientSelect.value || null;
        this.slotDuration = window.currentSlotDuration || 15;
      
        
        this.draggedRow = null;

        this.init();
    }

    init() {
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
        this.container.querySelectorAll('tr.draggable').forEach(row => {
            row.setAttribute('draggable', true);
            row.addEventListener('dragstart', (e) => this.onDragStart(e));
            row.addEventListener('dragover', (e) => this.onDragOver(e));
            row.addEventListener('drop', (e) => this.onDrop(e));
        });
    }

    onDragStart(event) {
        this.draggedRow = event.currentTarget;
        event.dataTransfer.effectAllowed = "move";
        event.dataTransfer.setData("text/plain", this.draggedRow.dataset.appointmentId);
    }

    onDragOver(event) {
        event.preventDefault();
    }

    onDrop(event) {
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
                toastr.success(data.message || 'Appointment updated!');
                this.loadAppointments();
            } else {
                toastr.error(data.message || 'Failed to update appointment.');
                this.loadAppointments();
            }
        } catch (err) {
            console.error(err);
            toastr.error('Error updating appointment.');
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

        const modalPatientNameInput = document.getElementById('modal-patient-name');
        if (modalPatientNameInput) {
            modalPatientNameInput.value = window.appConfig.patientName || '';
        } else {
            $('#patient-id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#bookAppointmentModal')
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
        modal.show();

        this.setupSlotChangeHandler(startTime);
    }

    async openManualBookingModal() {
        const form = document.getElementById('manualBookingForm');
        form.reset();

        const patientName = window.appConfig.patientName || '';
        const modalPatientNameInput = document.getElementById('hospital-patient-name');
        if (modalPatientNameInput) {
            modalPatientNameInput.value = patientName;
        } else {
            $('#hospital-patient-id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#manualBookingModal')  // important for modals!
            });
        }
        document.getElementById('hospital-dob').value = window.appConfig.patientDob || '';
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
        updateSlotUrl: window.appConfig.updateSlotUrl,
        csrfToken: window.appConfig.csrfToken,
        initialDate: window.appConfig.initialDate,
        storeHospitalAppointment: window.appConfig.storeHospitalAppointment
    });

    // Expose globally for onclick
    window.bookSlot = (time) => appointmentManager.bookSlot(time);
    window.openManualBookingModal = () => appointmentManager.openManualBookingModal();
    // **Initialize PopupForm for modal submission**
   // For Book Appointment Modal
    PopupForm.init('#bookAppointmentModal', '#bookAppointmentForm', (response) => {
        // Reload appointments after booking
        appointmentManager.loadAppointments();
        toastr.success(response.message || 'Appointment booked successfully!');
    });

    // For Manual Booking Modal
    PopupForm.init('#manualBookingModal', '#manualBookingForm', (response) => {
        // Do something after manual booking
        appointmentManager.loadAppointments();
        toastr.success(response.message || 'Manual booking saved successfully!');
    });

});

