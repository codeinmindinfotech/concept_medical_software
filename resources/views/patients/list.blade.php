   <table class="table table-hover align-middle text-nowrap" id="PatientTable">
        <thead>
            <tr>
                <th style="width: 40px;">#</th>
                <th>Appointment</th> 
                <th>Patient Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th style="width: 120px;">Date of Birth</th>
                <th>Status</th>
                @if(isset($trashed) && $trashed)
                <th>action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($patients as $index => $patient)

           
            <tr onclick="window.location='{{ guard_route('patients.show', $patient->id) }}'" style="cursor: pointer;">

                <td>{{ $patients->firstItem() + $index }}</td>
                <td>
                    @if($patient->nextAppointment)
                    @php
                        $rowClass =  $patient->nextAppointment?->appointmentType ? 'appointment-' . strtolower(str_replace(' ', '_', $patient->nextAppointment?->appointmentType?->value)) : '' ;
                    @endphp
                        <div>
                            <strong>
                                <!-- Appointment Type -->
                                <span class="badge {{ $rowClass }} text-dark me-3 px-3" style="min-width: 120px;">
                                    {{ $patient->nextAppointment?->appointmentType?->value ?? '-' }}
                                </span>
                            </strong><br>
                
                            <small class="text-muted">
                                {{ format_date($patient->nextAppointment->appointment_date) }}
                                {{ $patient->nextAppointment->start_time }}
                            </small>
                        </div>
                    @else
                        <span class="text-muted">No upcoming appointment</span>
                    @endif
                </td>
                
                <td>
                    <div class="align-items-center gap-2 d-flex">
                        @if ($patient->patient_picture)
                        <img src="{{ asset('storage/' . $patient->patient_picture) }}" alt="Patient Picture" class="rounded-circle" width="40" height="40">
                        @else
                        <div class="rounded-circle bg-secondary d-inline-block text-white text-center" style="width: 40px; height: 40px; line-height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        @endif
                        {{ $patient->full_name }}
                    </div>
                </td>
                <td>{{ $patient->address ?? '-' }}</td>
                <td>{{ $patient->phone ?? '' }}</td>
                <td>{{ format_date($patient->dob) }}</td>
                <td>
                    @if($patient->trashed())
                        <span class="badge bg-danger">Trashed</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </td>
                @if(isset($trashed) && $trashed)
                <td>
                    <form action="{{ route('patients.restore', $patient->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fas fa-undo"></i> Restore
                        </button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>


{!! $patients->appends(request()->query())->links('pagination::bootstrap-5') !!}