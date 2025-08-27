@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'DropDowns', 'url' =>guard_route('dropdowns.index')],
            ['label' => 'Show DropDown'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Show DropDown',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('dropdowns.index'),
        'isListPage' => false
    ])

<form action="{{guard_route('dropdowns.store') }}" method="POST">
    @csrf

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="code" class="form-label"><strong>Code</strong></label>
            <input type="text"
                   id="code"
                   name="code"
                   class="form-control @error('code') is-invalid @enderror"
                   value="{{ old('code') }}"
                   placeholder="CODE_TYPE"
                   oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9_]/g, '')">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="name" class="form-label"><strong>Name</strong></label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="Name">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2">
            <i class="fa-solid fa-floppy-disk"></i> Submit
        </button>
    </div>

</form>
</div>
@endsection