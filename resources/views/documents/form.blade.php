<div class="row g-4">
  {{-- ▶ Document Template Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Document Template</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <input type="hidden" name="template_id" value="{{ $template->id }}">

          <!-- Template Name -->
          <div class="col-md-6">
            <label for="name" class="form-label"><strong>Template Name <span class="text-danger">*</span></strong></label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name', $template->name ?? '') }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Template Type -->
          <div class="col-md-6">
            <label for="type" class="form-label"><strong>Template Type <span class="text-danger">*</span></strong></label>
            <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror">
              <option value="">-- Select Type --</option>
              <option value="letter" {{ old('type', $template->type ?? '') == 'letter' ? 'selected' : '' }}>Letter</option>
              <option value="form" {{ old('type', $template->type ?? '') == 'form' ? 'selected' : '' }}>Form</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- File Upload -->
          <div class="mb-3">
            <label for="file" class="form-label"><strong>Upload File {{ empty($template->file_path) ? '*' : '' }}</strong></label>
            <input 
                class="form-control @error('file') is-invalid @enderror" 
                type="file" 
                name="file" 
                id="file" 
                accept=".doc,.docx"
                {{ empty($tempPath) && empty($template->file_path) ? 'required' : '' }}
            >
            <div class="form-text">Accepted file types: .doc, .docx. Max size: 2MB.</div>
            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <input type="hidden" name="tempPath" id="tempPath" value="{{ old('tempPath', $tempPath ?? '') }}">
          </div>

          {{-- ▶ Tags Panel --}}
          <div class="col-12 mt-3">
            <div class="card shadow-sm">
              <div class="card-header">
                <h6 class="mb-0"><strong>Insert Tags</strong></h6>
              </div>
              <div class="card-body">
                @include('documents.keywords')
                {{-- <div id="tags-list" class="d-flex flex-wrap gap-2">
                  <button type="button" class="btn btn-outline-primary btn-sm tag-btn" data-tag="[FirstName]">[FirstName]</button>
                  <button type="button" class="btn btn-outline-primary btn-sm tag-btn" data-tag="[LastName]">[LastName]</button>
                  <button type="button" class="btn btn-outline-primary btn-sm tag-btn" data-tag="[DOB]">[DOB]</button>
                  <button type="button" class="btn btn-outline-primary btn-sm tag-btn" data-tag="[Gender]">[Gender]</button>
                </div> --}}
              </div>
            </div>
          </div>

          {{-- ▶ OnlyOffice Editor --}}
          <div id="onlyoffice-container" style="width: 100%; height: 90vh; display:none;">
            <div id="onlyoffice-editor"></div>
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- ▶ Submit --}}
  <div class="col-12 text-center mb-4">
    <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-floppy-disk"></i> Save Template
    </button>
  </div>
</div>

@push('scripts')
<script type="text/javascript" src="{{ rtrim(config('onlyoffice.server_url'), '/') }}/web-apps/apps/api/documents/api.js"></script>
<script>
let editorReady = false;
let docEditor = null; // keep reference globally
document.querySelectorAll('.tag-btn').forEach(btn => btn.disabled = true);

@if(!empty($template->file_path) && $template->id)
  loadExistingDocument("{{ guard_route('documents.loadFile', $template->id) }}");
@endif

function initEditor(data, title) {
    if (docEditor) {
        try {
            docEditor.destroyEditor();
            console.log("🧹 Previous OnlyOffice editor destroyed.");
        } catch (err) {
            console.warn("Failed to destroy old editor:", err);
        }
    }

    document.getElementById('onlyoffice-container').style.display = 'block';

    const config = {
        document: {
            fileType: data.fileType,
            key: data.key,
            title: title,
            url: data.url,
        },
        documentType: 'word',
        editorConfig: {
            mode: 'edit',
            user: {
                id: '{{ auth()->id() ?? "1" }}',
                name: "{{ auth()->user()->name ?? 'Guest' }}"
            },
            customization: { 
              forcesave: true ,
              plugins: {
                    autoload: ['myplugin'] // <-- load your plugin
                }
            },
            callbackUrl: data.callbackUrl // ✅ This tells OnlyOffice where to send changes

        },
        token: data.token,
        events: {
            onAppReady: () => console.log("OnlyOffice editor is ready"),
            onDocumentStateChange: function(event) {
                let status = null;

                // OnlyOffice sometimes returns a boolean instead of an object
                if (typeof event.data === "object" && event.data.status !== undefined) {
                    status = event.data.status;
                } else if (typeof event.data === "boolean") {
                    status = event.data ? 1 : 0; // treat 'true' as editing
                }
                console.log("Document state:", event.data, "Interpreted status:", status);
             },
            onRequestRefreshFile: function() {
                console.log("Editor requested file refresh.");
                docEditor.refreshFile(); // pull the latest version from your server
            }
        }

    };
    docEditor = new DocsAPI.DocEditor("onlyoffice-editor", config);
}

function loadExistingDocument(apiUrl) {
    fetch(apiUrl)
        .then(res => res.json())
        .then(data => {
            if (data.success) initEditor(data, data.title || "Existing Document");
            else console.error("Failed to load existing file.");
        })
        .catch(err => console.error("Error loading file:", err));
}
document.getElementById('file').addEventListener('change', function(e) {
    let file = e.target.files[0];
    if (!file) return;

    let formData = new FormData();
    formData.append('file', file);
    formData.append('document_id', "{{ $template->id }}"); // ✅ Pass the ID

    fetch("{{ guard_route('documents.tempUpload') }}", {
        method: "POST",
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return alert("❌ File upload failed.");
        initEditor({
            fileType: data.fileType,
            key: data.key,
            title: file.name,
            url: data.url,
            token: data.token,
            callbackUrl: "{{ url('/api/onlyoffice/document_callback') }}?document_id={{ $template->id }}" // ✅ Pass documentId
        }, file.name);
    })
    .catch(err => console.error("Upload error:", err));
});

document.querySelectorAll('.tag-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tag = btn.dataset.tag;

        const iframe = document.querySelector('#onlyoffice-editor iframe');
        if (!iframe) {
            alert("Editor not ready yet.");
            return;
        }

        iframe.contentWindow.postMessage({
            type: "insert-tag",
            tag: tag
        }, "*");
    });
});
// [
//         "ai",
//         "deepl",
//         "doc2md",
//         "languagetool",
//         "mathtype",
//         "ocr",
//         "photoeditor",
//         "speechrecognition",
//         "thesaurus",
//         "typograf",
//         "zotero"
// ]


</script>
@endpush
