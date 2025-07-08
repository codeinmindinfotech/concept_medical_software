@props([
    'label',
    'value' => '-',
    'col' => 6
])

<div class="col-md-{{ $col }}">
    <label class="form-label fw-bold">{{ $label }}</label>
    <p class="form-control-plaintext">{{ $value ?? '-' }}</p>
</div>