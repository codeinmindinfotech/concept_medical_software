@extends('layout.mainlayout')

@section('content')

@component('components.admin.breadcrumb')
@slot('title') Edit Patient @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent

<div class="content">
    <div class="container pt-3">

        <div class="row">
            <div class="col-lg-9 col-xl-10">

                
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
                <form action="{{guard_route('recalls.update', ['patient' => $patient->id, 'recall' => $recall->id]) }}" method="POST" data-ajax class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="mb-3">
                        <label for="recall_interval" class="form-label">Interval<span class="txt-error">*</span></label>
                        <select name="recall_interval" id="recall_interval" class="form-select select2" required>
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
                        <label for="recall_date" class="form-label">Recall Date<span class="txt-error">*</span></label>
                        <div class="cal-icon">
                            <input id="recall_date" name="recall_date" type="text" value="{{ old('recall_date', $recall->recall_date) }}" class="form-control datetimepicker" required placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status_id" class="form-label">Status<span class="txt-error">*</span></label>
                        <select name="status_id" id="status_id" class="form-select select2" required>
                            @foreach($statuses as $id => $value)
                            <option value="{{ $id }}" {{ old('status_id', $recall->status_id) == $id ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Note<span class="txt-error">*</span></label>
                        <textarea name="note" id="note" class="form-control" required>{{ old('note', $recall->note) }}</textarea>
                    </div>


                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Recall</button>
                    <a href="{{guard_route('recalls.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>
                </form>

            </div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>
</div>
@endsection