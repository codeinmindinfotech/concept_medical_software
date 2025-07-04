@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Doctors', 'url' => route('doctors.index')],
            ['label' => 'Edit Doctor'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Edit Doctor',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('doctors.index'),
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
        <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
            @csrf
            @method('PUT')
        
            <div class="row">
                <!-- Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" value="{{ old('name', $doctor->name) }}" class="form-control" placeholder="Name">
                    </div>
                </div>
        
                <!-- Contact Number -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Contact Number:</strong>
                        <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}" class="form-control" placeholder="Enter phone number" required>
                    </div>
                </div>
        
                <!-- Email -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" value="{{ old('email', $doctor->email) }}" class="form-control" placeholder="Enter email">
                    </div>
                </div>
        
                <!-- Gender -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Gender:</strong>
                        <select name="gender" class="form-control" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male" {{ old('gender', $doctor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $doctor->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Address:</strong>
                        <textarea name="address" class="form-control" required>{{ old('address', $doctor->address) }}</textarea>
                    </div>
                </div>

                <!-- Postal Code -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Postal Code:</strong>
                        <input type="text" name="postcode" value="{{ old('postcode', $doctor->postcode) }}" class="form-control" placeholder="Postal Code">
                    </div>
                </div>
        
                <!-- Notes -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong> Notes:</strong>
                        <textarea name="notes" class="form-control">{{ old('note', $doctor->note) }}</textarea>
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