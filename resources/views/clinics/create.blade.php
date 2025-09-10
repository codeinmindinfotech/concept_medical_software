@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Companies', 'url' => route('companies.index')],
            ['label' => 'Create Companies'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create Companies',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('companies.index'),
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

    <form action="{{ route('companies.store') }}" method="POST" class="validate-form">
        @csrf

        @include('companies.form')
        
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush