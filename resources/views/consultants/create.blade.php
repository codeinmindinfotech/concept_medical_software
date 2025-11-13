@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Consultants', 'url' =>guard_route('consultants.index')],
            ['label' => 'Create consultant'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Create consultant',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('consultants.index'),
        'isListPage' => false
    ])
  
  
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> Please fix the following errors:<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{guard_route('consultants.store') }}" method="POST" class="validate-form">
        @csrf

        @include('consultants.form')
        
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
