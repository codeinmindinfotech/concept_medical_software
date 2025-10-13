@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Documents', 'url' =>guard_route('documents.index')],
    ['label' => 'Show Document'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Show Document',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('documents.index'),
    'isListPage' => false
    ])

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Document Panel</h5>
                </div>
                <div class="card-body">
                    <h1>Preview: {{ $template->name }}</h1>
                    <div class="mb-3">
                        <strong>Type:</strong> {{ ucfirst($template->type) }}
                    </div>
                    <div class="mb-3">
                        <strong>File:</strong>
                        <textarea id="editor" name="content">{!! $html !!}</textarea>
                        {{-- <iframe src="http://137.184.194.64/onlyoffice/office/index.html?url={{asset('storage/' . $template->file_path)}}"
                            width="100%" height="600px" frameborder="0">
                        </iframe> --}}

                        <iframe src="http://137.184.194.64/onlyoffice/office/index.html?url=https://conceptmedicalpm.ie/storage/document_templates/KkqZ2ghGmwwaXBS1D1XmrOSVfZtopDuayNOqLpih.docx"
                            width="100%" height="600px" frameborder="0">
                        </iframe>                        
                        {{-- <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $template->file_path)) }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
