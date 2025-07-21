@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Doctors', 'url' => route('doctors.index')],
            ['label' => 'Show Doctor'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Show Doctor',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('doctors.index'),
        'isListPage' => false
    ])

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Doctor Panel</h5>
            </div>
            <div class="card-body">

                {{-- Nav Tabs --}}
                <ul class="nav nav-tabs mb-3" id="doctorTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="doctor-info-tab" data-bs-toggle="tab" data-bs-target="#doctor-info" type="button" role="tab">
                            <i class="fas fa-id-card me-1"></i>Doctor Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="doctor-audio-tab" data-bs-toggle="tab" data-bs-target="#doctor-audio" type="button" role="tab">
                            <i class="fas fa-microphone-alt me-1"></i>Audio Recordings
                        </button>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content" id="doctorTabContent">
                    {{-- Doctor Info Tab --}}
                    <div class="tab-pane fade show active" id="doctor-info" role="tabpanel" aria-labelledby="doctor-info-tab">
                        <div class="row g-3">
                            <x-show-field label="Name" :value="$doctor->name" />
                            <x-show-field label="Company" :value="$doctor->company" />
                            <x-show-field label="Salutation" :value="$doctor->salutation" />
                            <x-show-field label="Address" :value="$doctor->address" col="6" />
                            <x-show-field label="Postcode" :value="$doctor->postcode" col="2" />
                            <x-show-field label="Mobile" :value="$doctor->mobile" col="2" />
                            <x-show-field label="Phone" :value="$doctor->phone" col="2" />
                            <x-show-field label="Fax" :value="$doctor->fax" col="2" />
                            <x-show-field label="Email" :value="$doctor->email" />
                            <x-show-field label="Contact" :value="$doctor->contact" />
                            <x-show-field label="Contact Type" :value="$doctor->contactType->value ?? '-'" />
                            <x-show-field label="Payment Method" :value="$doctor->paymentMethod->value ?? '-'" />
                            <div class="col-12">
                                <label class="form-label fw-bold">Notes</label>
                                <div class="form-control-plaintext">{{ $doctor->note ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Doctor Audio Tab --}}
                    <div class="tab-pane fade" id="doctor-audio" role="tabpanel" aria-labelledby="doctor-audio-tab">
                        @if($doctor->audios->isNotEmpty())
                            <div class="list-group">
                                @foreach($doctor->audios as $audio)
                                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-microphone text-primary me-2"></i>
                                            <audio controls style="max-width: 300px;">
                                                <source src="{{ asset_url($audio->file_path) }}" type="audio/webm">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                        {{-- Patient Name --}}
                                        <div class="text-muted small">
                                            <i class="fas fa-user me-1 text-secondary"></i>
                                            @if($audio->patient)
                                                {{ $audio->patient->first_name }} {{ $audio->patient->surname }}
                                            @else
                                                {{  'Unknown Patient' }}
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            {{ $audio->created_at->format('d M Y, h:i A') }}
                                        </div>
                                        <div>
                                            <a href="{{ route('audios.edit', $audio->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Audio">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-1"></i> No audio recordings available for this doctor.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


</div>
@endsection