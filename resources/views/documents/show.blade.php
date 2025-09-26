@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Documents', 'url' =>guard_route('documents.index')],
    ['label' => 'Show Document'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Show Document',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('documents.index'),
    'isListPage' => false
    ])

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Document Panel</h5>
                </div>
                <div class="card-body">
                    <h1>Preview: {{ $template->name }}</h1>
                    <div class="mb-3">
                        <strong>Type:</strong> {{ ucfirst($template->type) }}
                    </div>
                    <div class="mb-3">
                        <strong>File:</strong>
                        <textarea id="editor" name="content">{!! $html !!}</textarea>

                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $template->file_path)) }}" width="100%" height="600px" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
{{-- <textarea id="editor_1" name="editor_html">{{ $html }}</textarea> --}}



@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor', {
        allowedContent: true
    });
</script>
<!-- TinyMCE Script -->
{{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea'
        , plugins: 'lists link image table code'
        , toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code'
        , menubar: false
    });

</script> --}}
{{-- <script src="https://cdn.tiny.cloud/1/r4t59mfzsp040hmthbgye6ft2gjndjrlw3iyrzp70ftzalri/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
  tinymce.init({
    selector: 'textarea',
    plugins: [
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists',
      'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed',
      'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste',
      'advtable', 'advcode', 'advtemplate', 'ai', 'uploadcare', 'mentions',
      'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect',
      'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
    uploadcare_public_key: '703f381c3ab7ea23be6d',
  });
</script> --}}
@endpush
