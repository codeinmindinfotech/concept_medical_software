@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container pt-3">
        <div class="card mb-4 shadow-sm p-3">
            <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                <h5 class="mb-0">
                    <i class="fas fa-user-clock me-2"></i> Notifications All
                </h5>
                <a href="{{ guard_route('notifications.index') }}" class="btn bg-primary text-white btn-light btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> List Notification
                </a>
            </div>
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



    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
<script>
    $('#NotificationTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 2, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
