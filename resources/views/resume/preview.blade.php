@extends('layouts.app')

@section('title', 'Preview Resume')
@section('page-title', 'Resume Preview')

@section('content')
<div class="preview-wrapper">
    <!-- Sidebar Panel -->
    <div class="preview-sidebar-panel">
        <h3><i class="fas fa-palette" style="color:var(--accent-primary);margin-right:6px;"></i>Template</h3>

        <div class="template-switcher">
            <label class="template-radio {{ $resume->template === 'classic' ? 'active' : '' }}" data-template="classic"
                onclick="switchTemplateInline('classic')">
                <input type="radio" name="template" value="classic" {{ $resume->template === 'classic' ? 'checked' : '' }}>
                <span class="template-radio-icon">🏛️</span> Classic
            </label>
            <label class="template-radio {{ $resume->template === 'modern' ? 'active' : '' }}" data-template="modern"
                onclick="switchTemplateInline('modern')">
                <input type="radio" name="template" value="modern" {{ $resume->template === 'modern' ? 'checked' : '' }}>
                <span class="template-radio-icon">🎨</span> Modern
            </label>
            <label class="template-radio {{ $resume->template === 'minimal' ? 'active' : '' }}" data-template="minimal"
                onclick="switchTemplateInline('minimal')">
                <input type="radio" name="template" value="minimal" {{ $resume->template === 'minimal' ? 'checked' : '' }}>
                <span class="template-radio-icon">✨</span> Minimal
            </label>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('resume.download', $resume->id) }}" class="btn btn-primary" id="btn-pdf-download">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('resume.edit', $resume->id) }}" class="btn btn-secondary" id="btn-edit-resume">
                <i class="fas fa-edit"></i> Edit Resume
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary" id="btn-back-dash">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </div>

        <div style="margin-top:20px;padding:14px;background:rgba(108,99,255,0.06);border-radius:var(--radius-sm);border:1px solid rgba(108,99,255,0.15);">
            <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:8px;"><i class="fas fa-info-circle" style="color:var(--info);"></i> Resume Info</div>
            <div style="font-size:0.82rem;color:var(--text-secondary);"><strong>{{ $resume->full_name }}</strong></div>
            <div style="font-size:0.78rem;color:var(--text-muted);">{{ $resume->job_title }}</div>
            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">Updated: {{ $resume->updated_at->diffForHumans() }}</div>
        </div>
    </div>

    <!-- Resume Preview -->
    <div class="preview-area" id="resume-preview-area">
        @include('resume.templates.' . $resume->template)
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTemplateInline(template) {
    document.querySelectorAll('.template-radio').forEach(r => {
        r.classList.toggle('active', r.dataset.template === template);
    });

    // Reload page with new template via AJAX / redirect
    const url = new URL(window.location.href);
    fetch(`{{ route('resume.preview', $resume->id) }}?template=${template}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.text())
    .then(html => {
        // Simple: reload page with template param
        window.location.href = `{{ route('resume.preview', $resume->id) }}?template=${template}`;
    });
}

// Handle template query param
const urlParams = new URLSearchParams(window.location.search);
const tpl = urlParams.get('template');
if (tpl) {
    document.querySelectorAll('.template-radio').forEach(r => {
        r.classList.toggle('active', r.dataset.template === tpl);
    });
}
</script>
@endpush
