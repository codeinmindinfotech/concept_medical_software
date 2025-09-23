@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Documents', 'url' =>guard_route('documents.index')],
    ['label' => 'Edit Document'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Edit Document',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('documents.index'),
    'isListPage' => false
    ])

    <form action="{{guard_route('documents.update', $template->id) }}" class="validate-form" method="POST">
        @csrf
        @method('PUT')

        @include('documents.form', [
        'template' => $template
        ])
    </form>

</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#template_body'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo', 'insertTable', 'tableColumn', 'tableRow', 'mergeTableCells' ]
        })
        .catch(error => {
            console.error(error);
        });
</script>
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#template_body'))
    .catch(error => {
      console.error(error);
    });
</script> --}}
@endpush
