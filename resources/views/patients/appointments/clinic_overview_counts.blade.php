<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Clinic Name</th>
                <th>Appointment Count</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointmentCounts as $appointment)
                <tr onclick="loadSlotsAndAppointments('{{ $appointment->clinic_id }}', '{{ $appointment->appointment_date }}')">
                    <td>
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2 rounded-circle"
                                style="width:12px; height:12px; background:{{ $appointment->clinic->color }};"></div>
                            <span>{{ $appointment->clinic_name }}</span>
                        </div>
                    </td>
                    <td>{{ $appointment->appointment_count }}</td>
                    <td>{{ format_date($appointment->appointment_date) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No clinics or appointments found </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
