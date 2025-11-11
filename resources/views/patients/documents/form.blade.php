<div class="row g-4">
  <div class="col-12">
      <div class="card shadow-sm">
          <div class="card-header">
              <h5 class="card-title mb-0"><strong>Patient Document</strong></h5>
          </div>
          <div class="card-body">
              <div class="row g-3">
                  <div class="col-md-6">
                    <input type="text" name="document_id" value="{{ $document->id }}">

                      <label class="form-label"><strong>Patient</strong></label>
                      <input type="text" class="form-control" value="{{ $patient->full_name ?? 'N/A' }}" disabled>
                      <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                  </div>

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

                  <div style="width: 100%; height: 80vh; display: none;" id="onlyoffice-container">
                    <div id="onlyoffice-editor"></div>
                </div>
                
              </div>
          </div>
      </div>
  </div>

  <div class="col-12 text-center mb-4">
      <button type="submit" class="btn btn-primary btn-sm">
          <i class="fa-solid"></i> {{ isset($document) ? 'Update Document' : 'Generate Document' }}
      </button>
  </div>
</div>
@push('scripts')
<script type="text/javascript" src="{{ rtrim(config('onlyoffice.server_url'), '/') }}/web-apps/apps/api/documents/api.js"></script>
<script>
@if(isset($config))

let editorReady = false;
let docEditor = null;

// Function to initialize OnlyOffice editor
function initOnlyOfficeEditor(data) {
    if (docEditor) {
        try { docEditor.destroyEditor(); } catch(e){ console.warn(e); }
    }

    document.getElementById('onlyoffice-container').style.display = 'block';
console.log(data.url);
    const config = {
        document: {
            fileType: data.fileType,
            key: data.key,
            title: data.title,
            url: data.url,
        },
        documentType: 'word',
        editorConfig: {
            mode: 'edit',
            user: {
                id: '{{ auth()->id() ?? 1 }}',
                name: '{{ auth()->user()->name ?? "Guest" }}',
            },
            customization: { forcesave: true },
            callbackUrl: data.callbackUrl 
        },
        token: data.token,
        events: {
            onAppReady: function() {
                editorReady = true;
                console.log("OnlyOffice editor is ready.");
            },
            onDocumentStateChange: function(event) {
                console.log("Document state:", event.data);
            },
            onRequestRefreshFile: function() {
                console.log("Editor requested file refresh.");
                docEditor.refreshFile(); // pull the latest version from your server
            }
        }
    };

    docEditor = new DocsAPI.DocEditor('onlyoffice-editor', config);
}

// Template change event
document.getElementById('document_template_id').addEventListener('change', function() {
    const templateId = this.value;
    const documentId = "{{ $document->id }}";
    if (!templateId) return;
alert("changed");

    fetch("{{ guard_route('patient-documents.previewTemplateCreate', $patient) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ template_id: templateId , document_id: documentId})
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            initOnlyOfficeEditor(data);
        } else {
            alert(data.message || 'Failed to load template');
        }
    })
    .catch(err => console.error('Preview error:', err));
});

    @if(isset($document))
        window.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('document_template_id');
            
            // If an existing template/document is selected, trigger the change
            // if (templateSelect.value) {
            //     templateSelect.dispatchEvent(new Event('change'));
            // } else 
            if ("{{ isset($document) && $document->file_path ? $document->file_path : '' }}") {
                // If there is an existing document, initialize it manually
                initOnlyOfficeEditor({
                    fileType: 'docx',
                    key: "{{ $config['document']['key'] }}",
                    title: "{{ $config['document']['title'] ?? 'Document' }}",
                    url: "{{ secure_asset('storage/' . $document->file_path) }}",
                    token: "{{ $config['token'] }}"
                });
            }
        });
    @endif
@endif
</script>
@endpush

    


