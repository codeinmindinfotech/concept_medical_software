@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Consultants', 'url' => route('consultants.index')],
            ['label' => 'Show consultant'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Show consultant',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('consultants.index'),
        'isListPage' => false
    ])

       
    
  <div class="card mb-4">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <strong>Code:</strong>
          <p>{{ $consultant->code }}</p>
        </div>
        <div class="col-md-6">
          <strong>Name:</strong>
          <p>{{ $consultant->name }}</p>
        </div>
        <div class="col-12">
          <strong>Address:</strong>
          <p>{{ $consultant->address }}</p>
        </div>
        <div class="col-md-6">
          <strong>Phone:</strong>
          <p>{{ $consultant->phone }}</p>
        </div>
        <div class="col-md-6">
          <strong>Fax:</strong>
          <p>{{ $consultant->fax ?? '-' }}</p>
        </div>
        <div class="col-md-6">
          <strong>Email:</strong>
          <p>{{ $consultant->email }}</p>
        </div>
        <div class="col-md-6">
          <strong>IMC No:</strong>
          <p>{{ $consultant->imc_no }}</p>
        </div>
        <div class="col-12">
          <strong>Insurance Providers:</strong>
          @if($consultant->insurances->isEmpty())
            <p><em>None assigned.</em></p>
          @else
            <ul class="list-group list-group-flush">
              @foreach($consultant->insurances as $ins)
                <li class="list-group-item">{{ $ins->code }}</li>
              @endforeach
            </ul>
          @endif
        </div>
        <div class="col-12 text-center">
          <strong>Image:</strong><br>
          @if($consultant->image)
            <img src="{{ asset('storage/'.$consultant->image) }}"
                 alt="{{ $consultant->name }}"
                 class="img-thumbnail mt-2"
                 style="max-height: 200px;">
          @else
            <p><em>No image provided.</em></p>
          @endif
        </div>
      </div>
    </div>
  </div>

    
</div>
@endsection