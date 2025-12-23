<?php $page = 'dropdowns-edit'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-1">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'DropDowns', 'url' =>guard_route('dropdowns.index')],
        ['label' => 'Edit DropDown'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit DropDown',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('dropdowns.index'),
        'isListPage' => false
        ])
        <form action="{{guard_route('dropdowns.update', $dropdown->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <!-- Code (non-editable) -->
                <div class="col-md-6">
                    <label for="code" class="form-label"><strong>Code</strong></label>
                    <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $dropdown->code) }}" placeholder="CODE_TYPE" disabled>
                </div>

                <!-- Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label"><strong>Name</strong></label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $dropdown->name) }}" placeholder="Name">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-sm mb-2 mt-2">
                    <i class="fa-solid fa-floppy-disk"></i> Submit
                </button>
            </div>

        </form>
    </div>
</div>
</div>
@endsection
