@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="sms" role="tabpanel">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{guard_route('sms.store', ['patient' => $patient->id]) }}" data-ajax class="needs-validation" novalidate method="POST">
    @csrf
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i> Default SMS Template
            </h5>
        </div>
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

        <div class="card-body">
            <div class="mb-3">
                <label for="sms_template" class="form-label">Select Template<span class="txt-error">*</span></label>
                <select id="sms_template" class="form-select" required>
                    <option value="">-- Select --</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" data-description="{{ $template->description }}">
                            {{ $template->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            @php
                $tags = [
                    '[AptType]' => 'Apt. Type',
                    '[Location]' => 'Location',
                    '[AptTests]' => 'Apt. Tests',
                    '[AptDate]' => 'Date',
                    '[AptTime]' => 'Time',
                ];
            @endphp

            <div class="mb-3">
                <label class="form-label">Insert Tag:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag => $label)
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('{{ $tag }}')">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="sms_content" class="form-label">SMS Content<span class="txt-error">*</span></label>
                <textarea id="sms_content" name="content" class="form-control @error('content') is-invalid @enderror" rows="5" oninput="updateCharCount()" required>{{ old('content') }}</textarea>
                <small class="text-muted mt-1 d-block">Character count: <span id="char_count">0</span></small>
                
                @error('content')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" name="send_option" value="now" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Send Now
                </button>
                <button type="submit" name="send_option" value="schedule" class="btn btn-primary">
                    <i class="fas fa-clock me-1"></i> Schedule Message
                </button>
                <a href="{{guard_route('sms.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>
            </div>
            
    </form>

        </div>
    </div>

   
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('sms_template');
    const smsContent = document.getElementById('sms_content');
    const charCount = document.getElementById('char_count');

    if (templateSelect) {
        templateSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const description = selected.getAttribute('data-description');
            smsContent.value = description || '';
            updateCharCount();
        });
    }

    window.insertTag = function(tag) {
        const start = smsContent.selectionStart;
        const end = smsContent.selectionEnd;
        const text = smsContent.value;

        smsContent.value = text.slice(0, start) + tag + text.slice(end);
        smsContent.focus();
        smsContent.selectionStart = smsContent.selectionEnd = start + tag.length;

        updateCharCount();
    };

    window.updateCharCount = function() {
        charCount.innerText = smsContent.value.length;
    };

});
</script>
@endpush
