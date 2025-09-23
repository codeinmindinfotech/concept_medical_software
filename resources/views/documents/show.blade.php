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
                <div class="p-4" style="border: 1px solid #ddd; background-color: #fff;">
                    {!! $rendered !!}
                </div>
            </div>
        </div>
    </div>
</div>


</div>
@endsection