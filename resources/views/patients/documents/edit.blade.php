@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('patient-documents.index', $patient->id)],
['label' => 'Edit Document'],
];
@endphp
@include('layout.partials.breadcrumb', [
    'pageTitle' => 'Edit Document',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('patient-documents.index', $patient->id),
    'isListPage' => false
])
<form action="{{guard_route('patient-documents.update',[$patient->id, $document->id]) }}" method="POST" data-ajax class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    @include('patients.documents.form', [
                'patient' => $patient,
                'templates' => $templates,
                'document'=> $document
                ])
</form>
@endsection

{{-- @push('scripts')
<script type="text/javascript" src="{{ rtrim(config('onlyoffice.server_url'), '/') }}/web-apps/apps/api/documents/api.js"></script>

<script>
    var config = @json($config);
    config.documentServerUrl = "{{ rtrim(config('onlyoffice.server_url'), '/') }}";

    config.token = "{{ $token }}";
    console.log(config.token);
    var docEditor = new DocsAPI.DocEditor("onlyoffice-editor", config);
    console.log(docEditor);
</script>
@endpush --}}
