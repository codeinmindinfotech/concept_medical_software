@props(['transcription','title'])

<div class="modal fade" id="transcriptionModal" tabindex="-1" aria-labelledby="transcriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transcriptionModalLabel">{{ $title ?? 'Transcription' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transcriptionModalBody" style="white-space: pre-wrap;">
                {{ $transcription ?? 'No transcription available.' }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
