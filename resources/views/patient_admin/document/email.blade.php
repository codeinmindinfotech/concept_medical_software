@extends('layout.mainlayout')

@section('content')

{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}

<div class="content">
    <div class="container pt-3">

        <div class="row">
            <div class="col-lg-9 col-xl-10">
                <div class="card-body">
                    @session('success')
                    <div class="alert alert-success" role="alert">
                        {{ $value }}
                    </div>
                    @endsession
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-1"></i>
                                Email / Attach Documents
                            </h5>
                            <a href="{{guard_route('patient-documents.index', $patient) }}"
                                class="btn bg-primary text-white btn-light btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Document List
                            </a>
                        </div>
                        <div class="card-body">
                            <form method="POST"
                                action="{{ guard_route('patient-documents.email.send', [$patient, $document]) }}"
                                data-ajax class="needs-validation" novalidate>
                                @csrf
                                {{-- class="validate-form" --}}
                                <div class="mb-3">
                                    <label for="sender_email" class="form-label">Sender Email:</label>
                                    <input type="email" class="form-control" name="sender_email" id="sender_email"
                                        placeholder="Enter Sender email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="to_email" class="form-label">To</label>
                                    <input type="email" class="form-control" name="to_email" id="to_email" value=""
                                        placeholder="Select recipient or enter email" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Send To:</label><br>
                                    <div>
                                        <label>
                                            <input type="radio" name="recipient_type" value="patient"
                                                data-email="{{ $patientEmail }}"> Patient
                                        </label>
                                        <label>
                                            <input type="radio" name="recipient_type" value="doctor"
                                                data-email="{{ $doctorEmail }}"> Doctor
                                        </label>
                                        <label>
                                            <input type="radio" name="recipient_type" value="referral"
                                                data-email="{{ $referralEmail }}"> Referral
                                        </label>
                                        <label>
                                            <input type="radio" name="recipient_type" value="other"
                                                data-email="{{ $otherEmail }}"> Other
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="cc" class="form-label">CC</label>
                                    <input type="text" class="form-control" name="cc" id="cc"
                                        placeholder="CC emails (comma-separated)">
                                </div>

                                <div class="mb-3">
                                    <label for="bcc" class="form-label">BCC</label>
                                    <input type="text" class="form-control" name="bcc" id="bcc"
                                        placeholder="BCC emails (comma-separated)">
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" name="subject" id="subject" required
                                        value="Patient Document: {{ $document->name }}">
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" name="message" id="message" rows="4"
                                        placeholder="Enter your message..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Send Email</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="recipient_type"]');
    const emailInput = document.getElementById('to_email');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            const email = this.getAttribute('data-email');
            emailInput.value = email || '';
        });
    });
});
</script>
@endpush