@extends('backend.theme.tabbed')

@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel" aria-labelledby="tab-tasks">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center  ">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i> Create Recall
            </h5>
            <a href="{{ route('recalls.recalls.index', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Recall List
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('recalls.recalls.store', ['patient' => $patient->id]) }}" class="validate-form" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">
                <input type="hidden" name="recall_id" id="recall_id">

                <div class="mb-3">
                    <label for="recall_interval" class="form-label">Interval</label>
                    <select name="recall_interval" id="recall_interval" class="form-select select2">
                        <option value="">-- Select --</option>
                        <option value="Today">Today</option>
                        <option value="6 weeks">6 weeks</option>
                        <option value="2 months">2 months</option>
                        <option value="3 months">3 months</option>
                        <option value="6 months">6 months</option>
                        <option value="1 year">1 year</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="recall_date" class="form-label"><strong>Recall Date</strong></label>
                    <div class="input-group">
                        <input id="recall_date" name="recall_date" type="text" class="form-control flatpickr" placeholder="YYYY-MM-DD">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status_id" class="form-label">Status</label>
                    <select name="status_id" id="status_id" class="form-select select2">
                        @foreach($statuses as $id => $value)
                        <option value="{{ $id }}" {{ old('status_id') == $id ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <textarea name="note" id="note" class="form-control"></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
