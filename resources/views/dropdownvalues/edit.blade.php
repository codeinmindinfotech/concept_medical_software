<?php $page = 'dropdownvalues-edit'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'DropDownValues', 'url' =>guard_route('dropdownvalues.index',$dropdown->id)],
        ['label' => 'Edit DropDownValue'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit DropDownValue',
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

        <form action="{{guard_route('dropdownvalues.update', $value->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Value:</strong>
                        <input type="text" name="value" value="{{ old('value', $value->value) }}" class="form-control" placeholder="Name">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm mb-2 mt-2">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
