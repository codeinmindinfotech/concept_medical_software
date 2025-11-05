<div class="row g-4">
  <div class="col-12">
      <div class="card shadow-sm">
          <div class="card-header">
              <h5 class="card-title mb-0"><strong>Patient Document</strong></h5>
          </div>
          <div class="card-body">
              <div class="row g-3">
                  <div class="col-md-6">
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

                  {{-- Only show editor when editing an actual patient document --}}
                  {{-- @if(isset($document) && $document->file_path)
                    <div style="width: 100%; height: 80vh;">
                      <div id="onlyoffice-editor"></div>
                    </div>
                  @endif --}}
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
<script type="text/javascript" src="https://office.conceptmedicalpm.ie/web-apps/apps/api/documents/api.js"></script>
<script>
@if(isset($config))
    const baseConfig = @json($config);

    function initEditor(fileUrl) {
        if (!fileUrl) return;

        // Clone base config so we always have a fresh object
        let editorConfig = JSON.parse(JSON.stringify(baseConfig));

        // Update the document URL
        editorConfig.document.url = fileUrl;

        // Force reload by changing the document key (MUST be unique)
        editorConfig.document.key = Date.now().toString();

        // Cleanly destroy any previous editor instance
        if (window.docEditor) {
            try {
                window.docEditor.destroyEditor();
            } catch (e) {
                console.warn("Error destroying old editor:", e);
            }
            window.docEditor = null;
        }

        // Make sure the container is visible
        const container = document.getElementById('onlyoffice-container');
        container.style.display = 'block';

        // (Optional) Reset the inner HTML to avoid stale iframe
        container.innerHTML = '<div id="onlyoffice-editor"></div>';

        // Initialize new editor instance
        window.docEditor = new DocsAPI.DocEditor("onlyoffice-editor", editorConfig);
    }

    // Load initial document (if exists)
    const currentFileUrl = "{{ isset($document) && $document->file_path ? secure_asset('storage/' . $document->file_path) : '' }}";
    if (currentFileUrl) initEditor(currentFileUrl);

    // Listen for template change
    document.getElementById('document_template_id').addEventListener('change', function() {
        const templateId = this.value;
        if (!templateId) return;

        $.ajax({
            url: "{{ guard_route('patient-documents.previewTemplateCreate', $patient) }}",
            method: "POST",
            data: {
                template_id: templateId,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.preview_url) {
                    console.log(response.preview_url);
                    initEditor(response.preview_url);
                } else {
                    alert("No preview URL received from server.");
                }
            },
            error: function(err) {
                console.error("Error loading template preview:", err);
                alert("Failed to load template preview.");
            }
        });
    });


    // @if(isset($config))
    //     let editorConfig = @json($config);

    //     function initEditor(fileUrl) {
    //         editorConfig.document.url = fileUrl;
    //         if (window.docEditor) {
    //             window.docEditor.destroyEditor();
    //         }
    //         window.docEditor = new DocsAPI.DocEditor("onlyoffice-editor", editorConfig);
    //     }

    //     let currentFileUrl = "{{ secure_asset('storage/' . $document->file_path ?? '') }}";
    //     if(currentFileUrl) initEditor(currentFileUrl);

    //     document.getElementById('document_template_id').addEventListener('change', function() {
    //         let templateId = this.value;
    //         if (!templateId) return;

    //         $.ajax({
    //             url: "{{ guard_route('patient-documents.previewTemplateCreate', $patient) }}",
    //             method: "POST",
    //             data: {
    //                 template_id: templateId,
    //                 _token: "{{ csrf_token() }}"
    //             },
    //             success: function(response) {
    //                 initEditor(response.preview_url);
    //             },
    //             error: function(err) {
    //                 console.error("Error loading template preview:", err);
    //                 alert("Failed to load template preview.");
    //             }
    //         });
    //     });
    // @endif
@endif
</script>
@endpush

    


