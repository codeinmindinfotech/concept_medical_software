@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
            ['label' => 'Document Management'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Document Management',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => guard_route('documents.create'),
        'isListPage' => true
    ])

    @session('success')
        <div class="alert alert-success">{{ $value }}</div>
    @endsession

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-alt"></i> Documents Management
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="documentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="list-tab" data-bs-toggle="tab" href="#list" role="tab">All Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="keywords-tab" data-bs-toggle="tab" href="#keywords" role="tab">Keyword Guidelines</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="library-tab" data-bs-toggle="tab" href="#library" role="tab">Online Forms Library</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="documentTabsContent">
                <!-- 1. List all documents -->
                <div class="tab-pane fade show active" id="list" role="tabpanel">
                    @include('documents.list', ['templates' => $templates])
                </div>

                <!-- 2. Keyword Guidelines -->
                <div class="tab-pane fade" id="keywords" role="tabpanel">
                    <h5>Keyword Guidelines</h5>
                    <p>
                    Use the following placeholders in your documents. Place keywords inside
                    <code>[]</code> — they are case-sensitive.
                    </p>
                
                    <div class="row">
                    <!-- 🩺 Column 1 -->
                    <div class="col-md-6">
                        <div class="accordion" id="accordionLeft">
                
                        <!-- Patient -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPatient">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePatient">
                                👤 Patient Details
                            </button>
                            </h2>
                            <div id="collapsePatient" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[Title]</code></li>
                                <li><code>[FirstName]</code></li>
                                <li><code>[SurName]</code></li>
                                <li><code>[PatientName]</code></li>
                                <li><code>[DOB]</code></li>
                                <li><code>[Age]</code></li>
                                <li><code>[Gender]</code></li>
                                <li><code>[PatientType]</code></li>
                                <li><code>[Occupation]</code></li>
                                <li><code>[PatientAddress1]</code></li>
                                <li><code>[PatientAddress2]</code></li>
                                <li><code>[PatientAddress3]</code></li>
                                <li><code>[PatientAddress4]</code></li>
                                <li><code>[PostCode]</code></li>
                                <li><code>[HomePhone]</code></li>
                                <li><code>[WorkPhone]</code></li>
                                <li><code>[Mobile]</code></li>
                                <li><code>[Email]</code></li>
                                <li><code>[PatientBalance]</code></li>
                                <li><code>[PatientNotes]</code></li>
                                <li><code>[PatientNeeds]</code></li>
                                <li><code>[Physical]</code></li>
                                <li><code>[History]</code></li>
                                <li><code>[Notes]</code></li>
                                <li><code>[Allergies]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        <!-- Account / Insurance -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAccount">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccount">
                                💼 Account / Insurance
                            </button>
                            </h2>
                            <div id="collapseAccount" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[AccountTo]</code></li>
                                <li><code>[AccountAddress1]</code></li>
                                <li><code>[AccountAddress2]</code></li>
                                <li><code>[AccountAddress3]</code></li>
                                <li><code>[AccountAddress4]</code></li>
                                <li><code>[Insurance]</code></li>
                                <li><code>[InsurancePlan]</code></li>
                                <li><code>[InsuranceAddress]</code></li>
                                <li><code>[PolicyNo]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        <!-- Next of Kin -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingNOK">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNOK">
                                👪 Next of Kin
                            </button>
                            </h2>
                            <div id="collapseNOK" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[NextOfKin]</code></li>
                                <li><code>[NextOfKinContact]</code></li>
                                <li><code>[NOKRelationship]</code></li>
                                <li><code>[NOKAddress]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        <!-- Clinical -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingClinical">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClinical">
                                🧬 Clinical & Medical
                            </button>
                            </h2>
                            <div id="collapseClinical" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[ReferralReason]</code></li>
                                <li><code>[Symptoms]</code></li>
                                <li><code>[Diagnosis]</code></li>
                                <li><code>[ClinicDiagnosis]</code></li>
                                <li><code>[Script]</code></li>
                                <li><code>[Script2]</code></li>
                                <li><code>[CurrentMeds]</code></li>
                                <li><code>[WT]</code></li>
                                <li><code>[WP]</code></li>
                                <li><code>[GLU]</code></li>
                                <li><code>[HBA1C]</code></li>
                                <li><code>[CHOL]</code></li>
                                <li><code>[LDL]</code></li>
                                <li><code>[TGS]</code></li>
                                <li><code>[HDL]</code></li>
                                <li><code>[CR]</code></li>
                                <li><code>[MICROLAB]</code></li>
                                <li><code>[GGT]</code></li>
                                <li><code>[AST]</code></li>
                                <li><code>[TSH]</code></li>
                                <li><code>[OTHERDIAB]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        </div>
                    </div>
                
                    <!-- 💳 Column 2 -->
                    <div class="col-md-6">
                        <div class="accordion" id="accordionRight">
                
                        <!-- Doctor -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingDoctor">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDoctor">
                                🧑‍⚕️ Doctor / Consultant
                            </button>
                            </h2>
                            <div id="collapseDoctor" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[DoctorName]</code></li>
                                <li><code>[DoctorTitle]</code></li>
                                <li><code>[DoctorFirstName]</code></li>
                                <li><code>[DoctorSurName]</code></li>
                                <li><code>[DoctorAddress1]</code></li>
                                <li><code>[DoctorAddress2]</code></li>
                                <li><code>[DoctorAddress3]</code></li>
                                <li><code>[DoctorAddress4]</code></li>
                                <li><code>[DoctorEmail]</code></li>
                                <li><code>[SalutionDoctor]</code></li>
                                <li><code>[GPPhone]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        <!-- Referral / Legal -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingReferral">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReferral">
                                📨 Referral / Legal / Other
                            </button>
                            </h2>
                            <div id="collapseReferral" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[ReferralName]</code></li>
                                <li><code>[ReferralAddress1]</code></li>
                                <li><code>[ReferralAddress2]</code></li>
                                <li><code>[ReferralAddress3]</code></li>
                                <li><code>[ReferralAddress4]</code></li>
                                <li><code>[ReferralRef]</code></li>
                                <li><code>[ReferralEmail]</code></li>
                                <li><code>[SalutionReferral]</code></li>
                                <li><code>[LegalName]</code></li>
                                <li><code>[LegalAddress1]</code></li>
                                <li><code>[LegalAddress2]</code></li>
                                <li><code>[LegalAddress3]</code></li>
                                <li><code>[LegalAddress4]</code></li>
                                <li><code>[LegalRef]</code></li>
                                <li><code>[LegalEmail]</code></li>
                                <li><code>[SalutionLegal]</code></li>
                                <li><code>[OtherTitle]</code></li>
                                <li><code>[OtherFirstName]</code></li>
                                <li><code>[OtherSurName]</code></li>
                                <li><code>[OtherName]</code></li>
                                <li><code>[OtherAddress1]</code></li>
                                <li><code>[OtherAddress2]</code></li>
                                <li><code>[OtherAddress3]</code></li>
                                <li><code>[OtherAddress4]</code></li>
                                <li><code>[OtherRef]</code></li>
                                <li><code>[OtherEmail]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        <!-- Appointment / Operation -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAptOp">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAptOp">
                                📅 Appointment & Operation
                            </button>
                            </h2>
                            <div id="collapseAptOp" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[AppDate]</code></li>
                                <li><code>[AppTime]</code></li>
                                <li><code>[AppType]</code></li>
                                <li><code>[AppLocation]</code></li>
                                <li><code>[AptFootNote]</code></li>
                                <li><code>[OpDate]</code></li>
                                <li><code>[OpDateLong]</code></li>
                                <li><code>[OpType]</code></li>
                                <li><code>[OpTime]</code></li>
                                <li><code>[OpLocation]</code></li>
                                <li><code>[OpCode]</code></li>
                                <li><code>[OpDescription]</code></li>
                                <li><code>[AdmDate]</code></li>
                                <li><code>[AdmTime]</code></li>
                                <li><code>[InvoiceAdmissionDate]</code></li>
                                <li><code>[InvoiceProcedureDate]</code></li>
                                <li><code>[DischargeDate]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        <!-- Invoice -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingInvoice">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInvoice">
                                💳 Invoice & Finance
                            </button>
                            </h2>
                            <div id="collapseInvoice" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[InvoiceDescription]</code></li>
                                <li><code>[InvoiceFee]</code></li>
                                <li><code>[VAT]</code></li>
                                <li><code>[NET]</code></li>
                                <li><code>[InvoiceTotal]</code></li>
                                <li><code>[InvoiceRef]</code></li>
                                <li><code>[InvoiceCode]</code></li>
                                <li><code>[AmountReceived]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        <!-- Misc -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingMisc">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMisc">
                                ⚙️ Miscellaneous
                            </button>
                            </h2>
                            <div id="collapseMisc" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                <li><code>[AltContact]</code></li>
                                <li><code>[Label]</code></li>
                                <li><code>[Envelope]</code></li>
                                <li><code>[pin]</code></li>
                                <li><code>[AptTest]</code></li>
                                <li><code>[Date]</code></li>
                                <li><code>[CurrentDate]</code></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                
                        </div>
                    </div>
                    </div>
                
                    <p class="mt-3">
                    <strong>Note:</strong> Keywords must be exactly as shown, including brackets and case.
                    </p>
                </div>
  
  
                <!-- 3. Online Forms Library -->
                <div class="tab-pane fade" id="library" role="tabpanel">
                    <h5>Online Forms Library</h5>
                    <form id="libraryForm" method="POST" action="{{ guard_route('documents.library.download') }}">
                        @csrf
                        <table class="table table-bordered table-hover" id="libraryTable">
                            <thead>
                                <tr>
                                    <th>Document Name</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                <tr data-id="{{ $template->id }}" style="cursor:pointer;">
                                    <td>{{ $template->name }}</td>
                                    <td>{{ $template->type ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                
                        <!-- Hidden input to store selected document ID -->
                        <input type="hidden" name="selected_doc" id="selected_doc">
                
                        <div class="mb-3">
                            <label for="newTemplateDescription" class="form-label">New Template Description (optional)</label>
                            <input type="text" class="form-control" name="newTemplateDescription" placeholder="Enter description to create new template">
                        </div>
                
                        <button type="submit" class="btn btn-success">Download Selected Document</button>
                    </form>
                </div>
                

            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#documentTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 2, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });

   let selectedRow = null;

$('#libraryTable tbody tr').on('click', function() {
    // Remove highlight from previous selection
    if (selectedRow) {
        $(selectedRow).removeClass('table-active');
    }

    // Highlight new selection
    $(this).addClass('table-active');
    selectedRow = this;

    // Store selected ID in hidden input
    $('#selected_doc').val($(this).data('id'));
});

// Form submit validation
$('#libraryForm').submit(function(e){
    if (!$('#selected_doc').val()) {
        e.preventDefault();
        alert('Please select a document!');
        return false;
    }

    if (!confirm('Are you sure? Current template should be replaced or new template added?')) {
        e.preventDefault();
        return false;
    }
});

</script>

   @endpush
