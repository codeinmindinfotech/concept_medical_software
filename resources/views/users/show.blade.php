@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
  {{-- Back Button --}}
  <div class="row mb-4">
    <div class="col">
      <a href="{{guard_route('users.index') }}" class="btn btn-primary">
         Back to List
      </a>
    </div>
  </div>

  {{-- User Information Card --}}
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">User Information</h5>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input type="text" readonly class="form-control-plaintext" value="{{ $user->name }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" readonly class="form-control-plaintext" value="{{ $user->email }}">
        </div>
        <div class="col-md-12">
          <label class="form-label">Roles</label>
          <div>
            @foreach($user->getRoleNames() as $role)
              <span class="badge bg-success">{{ $role }}</span>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
