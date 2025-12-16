@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Document Management
                        </h5>
                        @if(has_permission('document-list'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('documents.index') }}">
                            <i class="fas fa-plus-circle me-1"></i> List DocumentTemplate
                        </a>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{guard_route('documents.update', $template->id) }}" data-ajax class="needs-validation" novalidate method="POST">
                                        @csrf
                                        @method('PUT')
                                        @include('documents.form', [
                                        'template' => $template
                                        ])
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->
@endsection