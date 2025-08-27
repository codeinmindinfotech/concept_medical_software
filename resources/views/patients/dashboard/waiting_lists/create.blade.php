@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
  <div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center  ">
        <h5 class="mb-0">
            <i class="fas fa-user-clock me-2"></i> Waiting Management
        </h5>
        <a href="{{guard_route('waiting-lists.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
            <i class="fas fa-plus-circle me-1"></i> New Waiting
        </a>
    </div>
    <div class="card-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{guard_route('waiting-lists.store', ['patient' => $patient->id]) }}" class="validate-form" method="POST">
      @csrf
      <input type="hidden" name="patient_id" value="{{ $patient->id }}">

      <div class="mb-3">
        <label for="editVisitDate" class="form-label">Visit Date<span class="txt-error">*</span></label>
        <div class="input-group">
          <input id="editVisitDate" name="visit_date" type="text" class="form-control flatpickr" placeholder="YYYY-MM-DD"  >
          <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
        </div>
      </div>
      <div class="mb-3">
        <label for="note" class="form-label">Clinic<span class="txt-error">*</span></label>
        <select class="select2" id="editClinic" name="clinic_id">
            <option value="">-- Select Clinic --</option>
            @foreach($clinics as $clinic)
              <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
            @endforeach
        </select>
      </div>
      
      <div class="mb-3">
        <label for="editNote" class="form-label">Type Of appointment<span class="txt-error">*</span></label>
        <textarea class="form-control" id="editNote" name="consult_note" ></textarea>
      </div>
      <div class="mb-3">
        <label for="editCategory" class="form-label">Category<span class="txt-error">*</span></label>
        <select class="select2" id="editCategory" name="category_id" >
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->value }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
      <a href="{{guard_route('waiting-lists.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>

    </form>
  </div>
</div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush