@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Consultants', 'url' => route('consultants.index')],
            ['label' => 'Create consultant'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create consultant',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('consultants.index'),
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

    <form action="{{ route('consultants.store') }}" method="POST" class="validate-form">
        @csrf

        @include('consultants.form')
        
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
