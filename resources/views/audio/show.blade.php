@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('audios.index')],
            ['label' => 'Audio Recording Details'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Audio Recording Details',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('audios.index'),
        'isListPage' => false
    ])

    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
                    <i class="fas fa-file-audio fa-2x me-3"></i>
                    <h4 class="mb-0 fw-bold">Audio Recording Details</h4>
                </div>

                <div class="card-body p-4 bg-light rounded-bottom-4">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-4 text-primary fw-semibold fs-5 d-flex align-items-center">
                            <i class="fas fa-user me-2"></i> Patient Name
                        </div>
                        <div class="col-md-8 fs-5 text-dark">
                            {{ $audio->patient->first_name }} {{ $audio->patient->surname }}
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <div class="col-md-4 text-success fw-semibold fs-5 d-flex align-items-center">
                            <i class="fas fa-user-md me-2"></i> Doctor Name
                        </div>
                        <div class="col-md-8 fs-5 text-dark">
                            Dr. {{ $audio->doctor->name }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 text-info fw-semibold fs-5 d-flex align-items-center">
                            <i class="fas fa-microphone me-2"></i> Recorded Audio
                        </div>
                        <div class="col-md-8">
                            @if($audio->file_path)
                                <audio controls class="w-100 rounded shadow-sm border border-info" style="max-height: 90px;">
                                    <source src="{{ asset_url($audio->file_path) }}" type="audio/webm">
                                    Your browser does not support the audio element.
                                </audio>
                            @else
                                <span class="text-muted fst-italic">No audio recording available.</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <div class="col-md-4 text-warning fw-semibold fs-5 d-flex align-items-center">
                            <i class="fas fa-calendar-alt me-2"></i> Uploaded At
                        </div>
                        <div class="col-md-8 fs-6 text-secondary">
                            {{ $audio->created_at->format('F d, Y - h:i A') }}
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{guard_route('audios.index') }}" class="btn btn-outline-primary btn-lg shadow-sm">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
