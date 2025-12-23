@extends('layout.mainlayout')

@section('content')

@component('components.admin.breadcrumb')
    @slot('title') Create Recall @endslot
    @slot('li_1') Recalls @endslot
    @slot('li_2') Create @endslot
@endcomponent

<div class="content">
    <div class="container pt-3">

        <div class="row">
            <div class="col-lg-9 col-xl-10">

                <div class="card mb-4 shadow-sm p-3">
                    <div class="card-header d-flex justify-content-between align-items-center  mb-1 p-2">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Recall Management
                        </h5>
                        <a href="{{guard_route('recalls.index', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Recall List
                        </a>
                    </div>
                    <div class="card-body">                   
                        <form action="{{guard_route('recalls.store', ['patient' => $patient->id]) }}" data-ajax class="needs-validation" novalidate method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">
                            <input type="hidden" name="recall_id" id="recall_id">

                            <div class="mb-3">
                                <label for="recall_interval" class="form-label">Interval<span class="txt-error">*</span></label>
                                <select name="recall_interval" id="recall_interval" class="form-select select2" required>
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
                                <label for="recall_date" class="form-label">Recall Date<span class="txt-error">*</span></label>
                                <div class="cal-icon">
                                    <input id="recall_date" name="recall_date" type="text" required class="form-control datetimepicker" placeholder="YYYY-MM-DD">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status_id" class="form-label">Status<span class="txt-error">*</span></label>
                                <select name="status_id" id="status_id" class="form-select select2" required>
                                    @foreach($statuses as $id => $value)
                                    <option value="{{ $id }}" {{ old('status_id') == $id ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note<span class="txt-error">*</span></label>
                                <textarea name="note" id="note" class="form-control" required></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>
</div>

@endsection
