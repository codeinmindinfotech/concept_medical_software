@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Configurations', 'url' =>guard_route('configurations.index')],
            ['label' => 'Edit Company'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit Configuration',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('configurations.index'),
        'isListPage' => false
    ])

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{guard_route('configurations.update', $configuration->id) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
    
        @include('configurations.form', [
            'configuration' => $configuration
            ])

    </form>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush