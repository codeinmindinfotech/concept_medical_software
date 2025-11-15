<?php $page = 'Show Document'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Documents', 'url' =>guard_route('documents.index')],
        ['label' => 'Show Document'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Show Document',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('documents.index'),
        'isListPage' => false
        ])

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h1>Preview: {{ $document->name }}</h1>
                        <div class="mb-3">
                            <strong>Type:</strong> {{ ucfirst($document->type) }}
                        </div>
                        <div class="mb-3">
                            @if(isset($document) && $document->file_path)
                            <div style="width: 100%; height: 80vh;">
                                <div id="onlyoffice-viewer"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{ rtrim(config('onlyoffice.server_url'), '/') }}/web-apps/apps/api/documents/api.js"></script>
<script>
    let docEditor = null;

    // Clean up before re-init or when navigating away
    window.addEventListener('beforeunload', function() {
        if (docEditor) {
            try {
                docEditor.destroyEditor();
                console.log("🧹 OnlyOffice editor destroyed before unload.");
            } catch (err) {
                console.warn("Failed to destroy editor:", err);
            }
        }
    });

    function initViewer() {
        if (docEditor) {
            try {
                docEditor.destroyEditor();
            } catch (err) {
                console.warn("Error destroying old editor:", err);
            }
        }

        const config = {
            !!$config!!
        };
        docEditor = new DocsAPI.DocEditor("onlyoffice-viewer", config);
    }

    document.addEventListener("DOMContentLoaded", initViewer);

</script>
@endpush

