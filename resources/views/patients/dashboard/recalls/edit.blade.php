@extends('backend.theme.tabbed')

@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('recalls.recalls.update', ['patient' => $patient->id, 'recall' => $recall->id]) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

        <div class="mb-3">
            <label for="recall_interval" class="form-label">Interval</label>
            <select name="recall_interval" id="recall_interval" class="form-select select2">
                <option value="">-- Select --</option>
                @php
                    $intervals = ['Today', '6 weeks', '2 months', '3 months', '6 months', '1 year'];
                @endphp
                @foreach($intervals as $interval)
                    <option value="{{ $interval }}" {{ old('recall_interval', $recall->recall_interval) === $interval ? 'selected' : '' }}>
                        {{ $interval }}
                    </option>
                @endforeach
            </select>
            
        </div>
        <div class="mb-3">
            <label for="recall_date" class="form-label"><strong>Recall Date</strong></label>
            <div class="input-group">
                <input id="recall_date" name="recall_date" type="text" value="{{ old('recall_date', $recall->recall_date) }}" class="form-control flatpickr" placeholder="YYYY-MM-DD">
                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
            </div>
        </div>

        <div class="mb-3">
            <label for="status_id" class="form-label">Status</label>
            <select name="status_id" id="status_id" class="form-select select2">
                @foreach($statuses as $id => $value)
                <option value="{{ $id }}" {{ old('status_id', $recall->status_id) == $id ? 'selected' : '' }}>
                    {{ $value }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note</label>
            <textarea name="note" id="note" class="form-control">{{ old('note', $recall->note) }}</textarea>
        </div>


        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Task</button>
        <a href="{{ route('recalls.recalls.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
