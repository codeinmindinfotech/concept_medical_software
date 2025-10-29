@extends('backend.theme.tabbed')
@push('styles')
<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden; /* prevents scrollbars */
    }
    
    #onlyoffice-editor {
        width: 100%;
        height: 100vh; /* or 100% */
        border: none;
    }
    </style>
@endpush
@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('patient-documents.index', $patient->id)],
['label' => 'Edit Document'],
];
@endphp
@include('backend.theme.breadcrumb', [
    'pageTitle' => 'Edit Document',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('patient-documents.index', $patient->id),
    'isListPage' => false
])
<form action="{{guard_route('patient-documents.update',[$patient->id, $document->id]) }}" method="POST" class="validate-form">
    @csrf
    @method('PUT')

    @include('patients.documents.form', [
                'patient' => $patient,
                'templates' => $templates,
                'document'=> $document
                ])
</form>
@endsection

@push('scripts')
<script type="text/javascript" src="https://office.conceptmedicalpm.ie/web-apps/apps/api/documents/api.js"></script>

<script>
    var config = @json($config);
    config.documentServerUrl = "https://office.conceptmedicalpm.ie";

    config.token = "{{ $token }}";
    console.log(config.token);
    var docEditor = new DocsAPI.DocEditor("onlyoffice-editor", config);
    console.log(docEditor);
</script>
@endpush
