@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
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
            <div class="mb-3">
                <label for="manager_id" class="form-label">Select Manager:</label>
                <select name="user_ids[]" id="manager_id" class="select2" multiple required>
                    <option value="">-- Select Manager --</option>
                    {{-- Options will be filled dynamically via AJAX --}}
                </select>
                @error('manager_id')
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
@push('scripts')
<script>
    $(document).ready(function () {
        $('#company_id').on('change', function () {
            const companyId = $(this).val();
            $('#manager_id').empty().append('<option value="">Loading...</option>');
            const routeTemplate = "{{ route('company.manager', ['company' => '__COMPANY_ID__']) }}";
            const url = routeTemplate.replace('__COMPANY_ID__', companyId);
            if (companyId) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (response) {
                        $('#manager_id').empty().append('<option value="">-- Select Manager --</option>');
                        $.each(response, function (key, user) {
                            $('#manager_id').append(`<option value="${user.id}">${user.name}</option>`);
                        });
                    },
                    error: function () {
                        $('#manager_id').empty().append('<option value="">-- No managers found --</option>');
                    }
                });
            } else {
                $('#manager_id').empty().append('<option value="">-- Select Manager --</option>');
            }
        });
    });
</script>
@endpush
