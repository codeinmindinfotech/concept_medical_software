@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'DropDownValues', 'url' =>guard_route('dropdownvalues.index',$dropdown->id)],
            ['label' => 'Create DropDownValue'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create DropDownValue',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('dropdownvalues.index',$dropdown->id),
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

<form action="{{guard_route('dropdownvalues.store', $dropdown->id) }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <strong>Value:</strong>
                <input type="text" name="value" class="form-control"  value="{{ old('value') }}" placeholder="value">
            </div>
        </div>
            
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>
</div>
@endsection