@extends('backend.theme.default')

@section('content')
<div class="container">
    <h2>Send Notification</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ guard_route('notifications.send') }}">
        @csrf

        @if (has_role('superadmin'))
            <div class="mb-3">
                <label for="company_id" class="form-label">Select Company:</label>
                <select name="company_id" id="company_id" class="select2" required>
                    <option value="">-- Select Company --</option>
                    @foreach(\App\Models\Company::all() as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <div class="mb-3">
            <label for="message" class="form-label">Notification Message:</label>
            <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
            @error('message')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Send Notification</button>
    </form>
</div>
@endsection
