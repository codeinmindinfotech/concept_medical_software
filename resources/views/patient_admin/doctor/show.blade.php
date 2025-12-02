@extends('layout.mainlayout')

@section('content')
@php
$days = ['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'];
@endphp
<div class="content">
    <div class="container">

        <div class="row">

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Clinic Management
                    </h5>
                </div>
                <!-- General -->
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-header">
                                <div class="row align-items-center">
                                    <div class="col-auto profile-image">
                                        <a href="javascript:;">
                                            <img class="rounded-circle" alt="User Image"
                                                src="{{ URL::asset('/assets_admin/img/profiles/avatar-01.jpg') }}">
                                        </a>
                                    </div>
                                    <div class="col ml-md-n2 profile-user-info">
                                        <h4 class="user-name mb-0">{{$doctor->name}}</h4>
                                        <h6 class="text-muted">{{$doctor->email}}</h6>
                                        <div class="user-Location"><i class="fa-solid fa-location-dot"></i> {{$doctor->address}}
                                        , {{$doctor->postcode}}</div>
                                        <div class="about-text">{{ $doctor->note ?? '-' }}</div>
                                    </div>
                                 
                                </div>
                            </div>
                            <div class="profile-menu">
                                <ul class="nav nav-tabs nav-tabs-solid">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">About</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content profile-tab-cont">
        
                                <!-- Personal Details Tab -->
                                <div class="tab-pane fade show active" id="per_details_tab">
        
                                    <!-- Personal Details -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title d-flex justify-content-between">
                                                        <span>Personal Details</span>
                                                        @can('update', $doctor)
                                                            <a class="edit-link" href="{{guard_route('doctors.edit',$doctor->id) }}"><i
                                                                class="fa fa-edit me-1"></i>Edit</a>
                                                        @endcan
                                                    </h5>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Name</p>
                                                        <p class="col-sm-10">{{$doctor->salutation}} {{$doctor->name}}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Company</p>
                                                        <p class="col-sm-10">{{$doctor->company->name ?? ''}}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Email ID</p>
                                                        <p class="col-sm-10">{{$doctor->email}}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Mobile</p>
                                                        <p class="col-sm-10">{{$doctor->mobile}}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Phone</p>
                                                        <p class="col-sm-10">{{$doctor->phone}}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Fax</p>
                                                        <p class="col-sm-10">{{$doctor->fax}}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0">Address</p>
                                                        <p class="col-sm-10 mb-0">{{$doctor->address}},<br>
                                                            {{$doctor->postcode}}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0">Note</p>
                                                        <p class="col-sm-10 mb-0">{{ $doctor->note ?? '-' }}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0">Contact</p>
                                                        <p class="col-sm-10 mb-0">{{ $doctor->contact }}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0">Contact Type</p>
                                                        <p class="col-sm-10 mb-0">{{ $doctor->contactType->value ?? '-' }}</p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="col-sm-2 text-muted text-sm-right mb-0">Payment Method</p>
                                                        <p class="col-sm-10 mb-0">{{ $doctor->paymentMethod->value ?? '-' }}</p>
                                                    </div>
                                                    
                                                </div>
                                            </div>
        
                                        </div>
        
        
                                    </div>
                                    <!-- /Personal Details -->
        
                                </div>
                                <!-- /Personal Details Tab -->

        
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /General -->
            </div>

        </div>

    </div>
</div>

@endsection