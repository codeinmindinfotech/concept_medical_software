@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'DropDownValues', 'url' => route('dropdownvalues.index',$dropdown->id)],
            ['label' => 'Edit DropDownValue'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Edit DropDownValue',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('dropdownvalues.index',$dropdown->id),
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

    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <form action="{{ route('dropdownvalues.update', $value->id) }}" method="POST">
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
@endsection