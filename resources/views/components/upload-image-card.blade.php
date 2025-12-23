@props([
    'id' => 'uploadCard',              // Unique ID for the card
    'title' => 'Upload Image',          // Card title
    'existingImage' => null,            // Path of existing image
    'uploadRoute' => '#',               // Route to upload from file
    'webcamRoute' => '#',               // Route to upload from webcam
    'fieldName' => 'image'              // Input field name
])

<div class="card shadow-sm p-3" style="max-width: 400px; width: 100%;">
    <div class="card-header mb-1 p-2">
        <h5 class="mb-0"><i class="fas fa-camera"></i> {{ $title }}</h5>
    </div>

    <div class="card-body text-center">
        <h6 class="mb-3">{{ $title }}</h6>

        <!-- Buttons to switch upload method -->
        <div class="btn-group mb-3" role="group">
            <button class="btn btn-outline-primary" onclick="showUpload('{{ $id }}')">Upload Image</button>
            <button class="btn btn-outline-success" onclick="showWebcam('{{ $id }}')">Use Webcam</button>
        </div>

        <!-- Upload from Computer -->
        <div id="{{ $id }}_uploadBox" style="display:none;">
            <form id="{{ $id }}_browseUploadForm" action="{{ $uploadRoute }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="entity_id" value="{{ $id }}">
                <input type="file" name="{{ $fieldName }}" class="form-control mb-2" accept="image/*">
                <button class="btn btn-primary mt-2">Upload</button>
            </form>
        </div>

        <!-- Webcam Capture -->
        <div id="{{ $id }}_webcamBox" style="display:none;">
            <div id="{{ $id }}_camera" style="width:320px;height:240px;border:1px solid #ccc;"></div>
            <button onclick="takeSnapshot('{{ $id }}')" class="btn btn-success mt-2">Capture</button>

            <div id="{{ $id }}_snapshotResult" class="mt-3"></div>

            <form id="{{ $id }}_webcamForm" action="{{ $webcamRoute }}" method="POST">
                @csrf
                <input type="hidden" name="entity_id" value="{{ $id }}">
                <input type="hidden" name="{{ $fieldName }}_webcam" id="{{ $id }}_webcamImageField">
                <button type="submit" class="btn btn-primary mt-3" style="display:none;" id="{{ $id }}_uploadBtn">
                    Upload Captured Image
                </button>
            </form>
        </div>

        <!-- Existing Image -->
        @if($existingImage)
        <div class="mt-3">
            <label class="form-label"><strong>Existing:</strong></label>
            <div>
              <img src="{{ asset('storage/' . $existingImage) }}" width="150" height="100" class="img-thumbnail">
            </div>
        </div>
        @endif
    </div>
</div>
