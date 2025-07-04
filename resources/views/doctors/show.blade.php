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
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $doctor->name }}
            </div>
        </div>
    </div>
</div>
@endsection