@extends('backend.theme.tabbed')

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
{{ asset('storage/' . $document->file_path) }}
@endsection

@push('scripts')
<script type="text/javascript" src="http://137.184.194.64/web-apps/apps/api/documents/api.js"></script>

<script>
    var docEditor = new DocsAPI.DocEditor("placeholder", {
        document: {
            fileType: "docx",
            key: "{{ $document->id }}-{{ strtotime($document->updated_at) }}",
            title: "Document",
            url: "{{ asset('storage/' . $document->file_path) }}"
        },
        documentType: "word",
        editorConfig: {
            mode: "edit",
            callbackUrl: "http://137.184.194.64/onlyoffice/callback",
            // callbackUrl: "{{ route('onlyoffice.callback', $document->id) }}",
            user: {
                id: "{{ auth()->id() }}",
                name: "{{ auth()->user()->name }}"
            },
            customization: {
                forcesave: true
            }
        }
    });
</script>
@endpush
