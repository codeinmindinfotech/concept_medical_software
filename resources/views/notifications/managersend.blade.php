<?php $page = 'notifications-send'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Notifications', 'url' =>guard_route('notifications.index')],
        ['label' => 'Send Notification'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Send Notification',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('notifications.index'),
        'isListPage' => false
        ])

        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">

                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif


                        <form method="POST" action="{{ guard_route('notifications.managerform') }}">
                            @csrf

                            {{-- <div class="mb-3">
                                <label for="user_id" class="form-label">Select Superadmin:<span class="txt-error">*</span></label>
                                <select name="recipients[]" id="user_id" class="form-select form-control form-white select2" multiple>
                                    <option value="">-- Select Users --</option>
                                    @foreach($users as $user)
                                    <option value="user-{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="mb-3">
                                <label for="recipients" class="form-label">Select Recipients:<span class="txt-error">*</span></label>
                                <select name="recipients[]" class="form-select form-control form-white select2" multiple required>
                                    <optgroup label="Patients">
                                        @foreach($patients as $patient)
                                        <option value="patient-{{ $patient->id }}">{{ $patient->full_name }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Consultant">
                                        @foreach($consultants as $cons)
                                        <option value="consultant-{{ $cons->id }}">{{ $cons->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Notification Message:<span class="txt-error">*</span></label>
                                <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
                                @error('message')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <button type="submit" class="btn btn-primary">Send Notification</button>
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
