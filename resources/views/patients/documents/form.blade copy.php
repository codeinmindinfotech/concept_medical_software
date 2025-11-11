<div class="row g-4">
  {{-- ▶ Document Template Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Patient Document</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <input type="hidden" name="template_id" value="{{ $document->id }}">

          <div class="col-md-6">
              <label class="form-label"><strong>Patient</strong></label>
              <input type="text" class="form-control" value="{{ $patient->full_name ?? 'N/A' }}" disabled>
              <input type="hidden" name="patient_id" value="{{ $patient->id }}">
          </div>

          <!-- Template Type -->
          <div class="mb-3">
              <label for="document_template_id" class="form-label">Select Document Template</label>
              <select name="document_template_id" id="document_template_id" class="form-control" required>
                  <option value="">-- Select Template --</option>
                  @foreach($templates as $template)
                      <option value="{{ $template->id }}"
                          {{ (isset($document) && $document->document_template_id == $template->id) || old('document_template_id') == $template->id ? 'selected' : '' }}>
                          {{ $template->name }}
                      </option>
                  @endforeach
              </select>
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
            customization: { forcesave: true },
            callbackUrl: data.callbackUrl // ✅ This tells OnlyOffice where to send changes

        },
        token: data.token,
        events: {
            onAppReady: function() {
                editorReady = true;
                console.log("OnlyOffice editor is ready.");
            },
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
            callbackUrl: "{{ url('/api/onlyoffice/callback') }}?document_id={{ $template->id }}" // ✅ Pass documentId
        }, file.name);
    })
    .catch(err => console.error("Upload error:", err));
});

</script>
@endpush
