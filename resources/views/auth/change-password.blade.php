<?php $page = 'user.update'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid px-1">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
                ['label' => 'Change User Password'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Change User Password',
            'breadcrumbs' => $breadcrumbs,
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
        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{guard_route('password.user.update') }}" data-ajax class="needs-validation" novalidate >
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label>Name <span class="txt-error">*</span></label>
                                    <input type="text" class="form-control" value="{{ user_name() }}" disabled>
                                </div>
                                
                                <div class="mb-3">
                                    <label>New Password <span class="txt-error">*</span></label>
                                    <input type="password" name="new_password" class="form-control" required>
                                    @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label>Confirm New Password <span class="txt-error">*</span></label>
                                    <input type="password" name="new_password_confirmation" class="form-control" required >
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /General -->

            </div>
        </div>

    </div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->
@endsection