@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Resume Hub')

@section('content')
<!-- Welcome Banner -->
<div class="dashboard-hero">
    <div class="hero-content">
        <div class="hero-greeting">
            <span class="greeting-badge"><i class="fas fa-sparkles"></i> AI-Powered Career Hub</span>
            <h1 class="hero-title">Welcome back, {{ Auth::user()->name }} 👋</h1>
            <p class="hero-subtitle">Manage, optimize, and export high-impact resumes tailored to land interviews.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('resume.create') }}" class="btn btn-primary" id="hero-create-btn">
                <i class="fas fa-plus"></i> Create New Resume
            </a>
            <button type="button" class="btn btn-secondary" onclick="toggleChatbot();" id="hero-ai-btn">
                <i class="fas fa-robot"></i> Ask AI Assistant
            </button>
        </div>
    </div>
</div>

<!-- Advanced Stats Strip -->
<div class="stats-grid stats-grid-5">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-file-alt"></i></div>
        <div class="stat-details">
            <div class="stat-value" data-count="{{ $stats['total'] }}">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Resumes</div>
            <div class="stat-trend"><i class="fas fa-layer-group"></i> In your workspace</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-details">
            <div class="stat-value" data-count="{{ $stats['published'] }}">{{ $stats['published'] }}</div>
            <div class="stat-label">Published & Ready</div>
            <div class="stat-trend text-success"><i class="fas fa-share-alt"></i> Ready to send</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-edit"></i></div>
        <div class="stat-details">
            <div class="stat-value" data-count="{{ $stats['drafts'] }}">{{ $stats['drafts'] }}</div>
            <div class="stat-label">Drafts</div>
            <div class="stat-trend text-warning"><i class="fas fa-clock"></i> In progress</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon cyan"><i class="fas fa-chart-line"></i></div>
        <div class="stat-details">
            <div class="stat-value">{{ $stats['avg_strength'] }}%</div>
            <div class="stat-label">Avg. ATS Score</div>
            <div class="stat-trend {{ $stats['avg_strength'] >= 75 ? 'text-success' : 'text-cyan' }}">
                <i class="fas fa-tachometer-alt"></i> Content strength
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-bolt"></i></div>
        <div class="stat-details">
            <div class="stat-value">{{ $stats['total_skills'] }}</div>
            <div class="stat-label">Skills Logged</div>
            <div class="stat-trend"><i class="fas fa-briefcase"></i> Across {{ $stats['total_experience'] }} experiences</div>
        </div>
    </div>
</div>

<!-- Quick Template Showcase (Start Faster) -->
<div class="template-showcase-section">
    <div class="showcase-header">
        <div class="showcase-title-wrap">
            <h3 class="showcase-title"><i class="fas fa-palette"></i> Quick-Start From a Curated Template</h3>
            <p class="showcase-subtitle">Choose a professionally designed layout optimized for applicant tracking systems.</p>
        </div>
        <button type="button" class="btn btn-sm btn-ghost" id="toggle-templates-btn" onclick="toggleTemplateShowcase()">
            <span id="templates-toggle-text">Hide</span> <i class="fas fa-chevron-up" id="templates-toggle-icon"></i>
        </button>
    </div>

    <div class="template-showcase-grid" id="template-showcase-grid">
        <!-- Modern Tech -->
        <div class="showcase-card showcase-modern">
            <div class="showcase-card-header">
                <span class="showcase-tag">Popular</span>
                <span class="template-pill modern">Modern</span>
            </div>
            <div class="showcase-preview-box">
                <div class="mock-sidebar"></div>
                <div class="mock-content">
                    <div class="mock-line title"></div>
                    <div class="mock-line sub"></div>
                    <div class="mock-line"></div>
                </div>
            </div>
            <div class="showcase-info">
                <h4>Modern Tech</h4>
                <p>Dual-column layout with sidebar. Perfect for developers, designers, and tech innovators.</p>
                <a href="{{ route('resume.create', ['template' => 'modern']) }}" class="btn btn-secondary btn-sm btn-block">
                    <i class="fas fa-magic"></i> Use Modern Template
                </a>
            </div>
        </div>

        <!-- Classic Professional -->
        <div class="showcase-card showcase-classic">
            <div class="showcase-card-header">
                <span class="showcase-tag">ATS Gold Standard</span>
                <span class="template-pill classic">Classic</span>
            </div>
            <div class="showcase-preview-box">
                <div class="mock-content full">
                    <div class="mock-line center title"></div>
                    <div class="mock-line center sub"></div>
                    <div class="mock-divider"></div>
                    <div class="mock-line"></div>
                    <div class="mock-line"></div>
                </div>
            </div>
            <div class="showcase-info">
                <h4>Classic Corporate</h4>
                <p>Traditional single-column format. Ideal for finance, corporate, legal, and academic roles.</p>
                <a href="{{ route('resume.create', ['template' => 'classic']) }}" class="btn btn-secondary btn-sm btn-block">
                    <i class="fas fa-magic"></i> Use Classic Template
                </a>
            </div>
        </div>

        <!-- Minimalist Sleek -->
        <div class="showcase-card showcase-minimal">
            <div class="showcase-card-header">
                <span class="showcase-tag">Clean & Elegant</span>
                <span class="template-pill minimal">Minimal</span>
            </div>
            <div class="showcase-preview-box">
                <div class="mock-content full minimal">
                    <div class="mock-line title"></div>
                    <div class="mock-line sub"></div>
                    <div class="mock-grid-dots"></div>
                </div>
            </div>
            <div class="showcase-info">
                <h4>Minimalist Sleek</h4>
                <p>Clean typography, generous whitespace, and laser-focused readability for modern creatives.</p>
                <a href="{{ route('resume.create', ['template' => 'minimal']) }}" class="btn btn-secondary btn-sm btn-block">
                    <i class="fas fa-magic"></i> Use Minimal Template
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area: Resumes & Optimization Widget -->
<div class="dashboard-layout">
    <!-- Left / Primary: Resumes List & Control Bar -->
    <div class="dashboard-main-col">
        <!-- Interactive Control Bar -->
        <div class="control-bar-card">
            <div class="control-bar-top">
                <!-- Search Input -->
                <div class="search-input-wrap">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="resume-search" class="control-search-input" placeholder="Search by resume title, role, or applicant name..." oninput="filterResumes()">
                    <button type="button" id="clear-search-btn" class="search-clear-btn" onclick="clearSearch()" style="display:none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- View Switcher -->
                <div class="view-switcher" role="group" aria-label="View toggle">
                    <button type="button" class="view-btn active" id="view-grid-btn" onclick="switchView('grid')" title="Grid View">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button type="button" class="view-btn" id="view-list-btn" onclick="switchView('list')" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <div class="control-bar-bottom">
                <!-- Filter Tabs -->
                <div class="filter-tabs">
                    <button type="button" class="filter-tab active" data-filter="all" onclick="setStatusFilter('all')">
                        All <span class="tab-badge">{{ $stats['total'] }}</span>
                    </button>
                    <button type="button" class="filter-tab" data-filter="published" onclick="setStatusFilter('published')">
                        Published <span class="tab-badge">{{ $stats['published'] }}</span>
                    </button>
                    <button type="button" class="filter-tab" data-filter="draft" onclick="setStatusFilter('draft')">
                        Drafts <span class="tab-badge">{{ $stats['drafts'] }}</span>
                    </button>
                    <button type="button" class="filter-tab" data-filter="high-ats" onclick="setStatusFilter('high-ats')">
                        High ATS (80%+) <span class="tab-badge">{{ $resumes->where('strength_score', '>=', 80)->count() }}</span>
                    </button>
                </div>

                <!-- Secondary Select Filters -->
                <div class="filter-dropdowns">
                    <div class="select-wrapper">
                        <select id="filter-template" class="control-select" onchange="filterResumes()">
                            <option value="">All Templates</option>
                            <option value="classic">Classic</option>
                            <option value="modern">Modern</option>
                            <option value="minimal">Minimal</option>
                        </select>
                    </div>

                    <div class="select-wrapper">
                        <select id="sort-resumes" class="control-select" onchange="sortResumes()">
                            <option value="updated-desc">Recently Updated</option>
                            <option value="updated-asc">Oldest First</option>
                            <option value="score-desc">Highest ATS Score</option>
                            <option value="score-asc">Lowest ATS Score</option>
                            <option value="title-asc">Title (A-Z)</option>
                            <option value="title-desc">Title (Z-A)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @if($resumes->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-file-signature"></i></div>
                <h3>No Resumes Created Yet</h3>
                <p>Build your first professional resume with AI assistance and ATS-optimized templates.</p>
                <div class="empty-state-actions">
                    <a href="{{ route('resume.create') }}" class="btn btn-primary btn-lg" id="empty-create-btn">
                        <i class="fas fa-magic"></i> Create My First Resume
                    </a>
                </div>
            </div>
        @else
            <!-- Filter Empty Notice (hidden by default) -->
            <div id="no-filter-results" class="empty-filter-state" style="display:none;">
                <div class="empty-filter-icon"><i class="fas fa-search-minus"></i></div>
                <h4>No matching resumes found</h4>
                <p>Try adjusting your search terms, template filter, or status tab.</p>
                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAllFilters()">
                    <i class="fas fa-redo"></i> Reset Filters
                </button>
            </div>

            <!-- 1. GRID VIEW -->
            <div class="resumes-grid" id="resumes-grid-view">
                @foreach($resumes as $resume)
                @php
                    $score = $resume->strength_score;
                    $scoreColor = $resume->strength_badge_color;
                    $expCount = $resume->workExperiences->count();
                    $eduCount = $resume->educations->count();
                    $skillsCount = $resume->skills->count();
                    $projCount = $resume->projects->count();
                @endphp
                <div class="resume-card"
                     id="resume-card-{{ $resume->id }}"
                     data-id="{{ $resume->id }}"
                     data-title="{{ strtolower($resume->title) }}"
                     data-name="{{ strtolower($resume->full_name ?? '') }}"
                     data-role="{{ strtolower($resume->job_title ?? '') }}"
                     data-status="{{ $resume->is_draft ? 'draft' : 'published' }}"
                     data-template="{{ strtolower($resume->template) }}"
                     data-score="{{ $score }}"
                     data-updated="{{ $resume->updated_at->timestamp }}">

                    <!-- Card Header / Preview Area -->
                    <div class="resume-card-preview template-bg-{{ $resume->template }}">
                        <a href="{{ route('resume.preview', $resume->id) }}" class="preview-overlay-link" title="Open Preview">
                            <div class="mock-resume-sheet">
                                <div class="mock-sheet-header"></div>
                                <div class="mock-sheet-lines">
                                    <span></span><span></span><span></span>
                                </div>
                            </div>
                        </a>

                        <div class="resume-card-top-badges">
                            <span class="resume-card-template-badge template-{{ $resume->template }}">
                                <i class="fas {{ $resume->template === 'modern' ? 'fa-brush' : ($resume->template === 'minimal' ? 'fa-feather' : 'fa-landmark') }}"></i>
                                {{ ucfirst($resume->template) }}
                            </span>

                            <!-- ATS Score Ring / Pill -->
                            <div class="ats-score-pill ats-{{ $scoreColor }}" title="ATS Strength Score: {{ $score }}%">
                                <span class="score-dot"></span>
                                <span class="score-number">{{ $score }}% ATS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="resume-card-body">
                        <div class="resume-card-header-row">
                            <div>
                                <h3 class="resume-card-name" title="{{ $resume->title }}">{{ $resume->title }}</h3>
                                @if($resume->job_title)
                                    <div class="resume-card-job-role"><i class="fas fa-id-badge"></i> {{ $resume->job_title }}</div>
                                @endif
                            </div>
                            <div>
                                @if($resume->is_draft)
                                    <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> Draft</span>
                                @else
                                    <span class="badge badge-published"><i class="fas fa-check"></i> Published</span>
                                @endif
                            </div>
                        </div>

                        <div class="resume-card-meta">
                            @if($resume->full_name)
                                <span class="meta-item"><i class="fas fa-user"></i> {{ $resume->full_name }}</span>
                            @endif
                            <span class="meta-item"><i class="fas fa-clock"></i> {{ $resume->updated_at->diffForHumans() }}</span>
                        </div>

                        <!-- Progress Bar for Resume Strength -->
                        <div class="resume-strength-container">
                            <div class="strength-labels">
                                <span>Completeness</span>
                                <span class="strength-percentage text-{{ $scoreColor }}">{{ $score }}%</span>
                            </div>
                            <div class="strength-track">
                                <div class="strength-bar strength-bar-{{ $scoreColor }}" style="width: {{ $score }}%;"></div>
                            </div>
                        </div>

                        <!-- Content Stats Chips -->
                        <div class="resume-chips-row">
                            <span class="mini-chip" title="{{ $expCount }} Work Experiences">
                                <i class="fas fa-briefcase"></i> {{ $expCount }} Exp
                            </span>
                            <span class="mini-chip" title="{{ $eduCount }} Education entries">
                                <i class="fas fa-graduation-cap"></i> {{ $eduCount }} Edu
                            </span>
                            <span class="mini-chip" title="{{ $skillsCount }} Skills listed">
                                <i class="fas fa-code"></i> {{ $skillsCount }} Skills
                            </span>
                            @if($projCount > 0)
                            <span class="mini-chip" title="{{ $projCount }} Projects">
                                <i class="fas fa-folder"></i> {{ $projCount }} Proj
                            </span>
                            @endif
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="resume-card-actions">
                            <a href="{{ route('resume.preview', $resume->id) }}" class="btn btn-secondary btn-sm" id="btn-preview-{{ $resume->id }}" title="Preview Resume">
                                <i class="fas fa-eye"></i> Preview
                            </a>
                            <a href="{{ route('resume.edit', $resume->id) }}" class="btn btn-secondary btn-sm" id="btn-edit-{{ $resume->id }}" title="Edit Resume">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('resume.download', $resume->id) }}" class="btn btn-success btn-sm" id="btn-download-{{ $resume->id }}" title="Download PDF">
                                <i class="fas fa-download"></i> PDF
                            </a>

                            <!-- Duplicate Action Form -->
                            <form method="POST" action="{{ route('resume.duplicate', $resume->id) }}" style="display:inline;" onsubmit="return confirmDuplicate('{{ addslashes($resume->title) }}')">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm" id="btn-duplicate-{{ $resume->id }}" title="Duplicate this resume">
                                    <i class="fas fa-clone"></i>
                                </button>
                            </form>

                            <!-- Copy Preview Link -->
                            <button type="button" class="btn btn-ghost btn-sm" onclick="copyPreviewLink('{{ route('resume.preview', $resume->id) }}')" title="Copy preview link">
                                <i class="fas fa-link"></i>
                            </button>

                            <!-- Delete Action Form -->
                            <form method="POST" action="{{ route('resume.destroy', $resume->id) }}" style="display:inline;" onsubmit="return confirmDelete(this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" id="btn-delete-{{ $resume->id }}" title="Delete Resume">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Add New Resume Blank Card -->
                <a href="{{ route('resume.create') }}" class="resume-card add-resume-card" id="add-resume-card">
                    <div class="add-resume-inner">
                        <div class="add-icon-box">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="add-title">Create New Resume</div>
                        <p class="add-sub">Start with step-by-step guidance & AI</p>
                    </div>
                </a>
            </div>

            <!-- 2. LIST / TABLE VIEW (Toggled via JS) -->
            <div class="resumes-list-view" id="resumes-list-view" style="display: none;">
                <div class="table-responsive">
                    <table class="resumes-table">
                        <thead>
                            <tr>
                                <th>Resume Title</th>
                                <th>Template</th>
                                <th>ATS Score</th>
                                <th>Sections</th>
                                <th>Status</th>
                                <th>Last Modified</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumes as $resume)
                            @php
                                $score = $resume->strength_score;
                                $scoreColor = $resume->strength_badge_color;
                                $expCount = $resume->workExperiences->count();
                                $eduCount = $resume->educations->count();
                                $skillsCount = $resume->skills->count();
                            @endphp
                            <tr class="resume-table-row"
                                id="resume-row-{{ $resume->id }}"
                                data-id="{{ $resume->id }}"
                                data-title="{{ strtolower($resume->title) }}"
                                data-name="{{ strtolower($resume->full_name ?? '') }}"
                                data-role="{{ strtolower($resume->job_title ?? '') }}"
                                data-status="{{ $resume->is_draft ? 'draft' : 'published' }}"
                                data-template="{{ strtolower($resume->template) }}"
                                data-score="{{ $score }}"
                                data-updated="{{ $resume->updated_at->timestamp }}">
                                <td>
                                    <a href="{{ route('resume.preview', $resume->id) }}" class="table-resume-link">
                                        <div class="table-resume-title">{{ $resume->title }}</div>
                                        <div class="table-resume-sub">{{ $resume->full_name ?? 'No name set' }} {{ $resume->job_title ? '· ' . $resume->job_title : '' }}</div>
                                    </a>
                                </td>
                                <td>
                                    <span class="template-pill {{ $resume->template }}">{{ ucfirst($resume->template) }}</span>
                                </td>
                                <td>
                                    <div class="table-ats-wrap">
                                        <span class="table-ats-text text-{{ $scoreColor }}">{{ $score }}%</span>
                                        <div class="table-ats-bar">
                                            <div class="table-ats-fill strength-bar-{{ $scoreColor }}" style="width: {{ $score }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-chips">
                                        <span class="mini-chip">{{ $expCount }} Exp</span>
                                        <span class="mini-chip">{{ $eduCount }} Edu</span>
                                        <span class="mini-chip">{{ $skillsCount }} Skills</span>
                                    </div>
                                </td>
                                <td>
                                    @if($resume->is_draft)
                                        <span class="badge badge-draft">Draft</span>
                                    @else
                                        <span class="badge badge-published">Published</span>
                                    @endif
                                </td>
                                <td class="table-date">
                                    {{ $resume->updated_at->diffForHumans() }}
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('resume.preview', $resume->id) }}" class="btn-icon" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('resume.edit', $resume->id) }}" class="btn-icon" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('resume.download', $resume->id) }}" class="btn-icon text-success" title="Download PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form method="POST" action="{{ route('resume.duplicate', $resume->id) }}" style="display:inline;" onsubmit="return confirmDuplicate('{{ addslashes($resume->title) }}')">
                                            @csrf
                                            <button type="submit" class="btn-icon" title="Duplicate">
                                                <i class="fas fa-clone"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn-icon" onclick="copyPreviewLink('{{ route('resume.preview', $resume->id) }}')" title="Copy Link">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <form method="POST" action="{{ route('resume.destroy', $resume->id) }}" style="display:inline;" onsubmit="return confirmDelete(this)">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon text-danger" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Right / Secondary Column: ATS Optimizer & Career Tooling -->
    <aside class="dashboard-side-col">
        <!-- ATS Optimizer Card -->
        <div class="side-widget-card ats-widget">
            <div class="widget-header">
                <div class="widget-icon-box purple"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h4 class="widget-title">ATS Readiness Checklist</h4>
                    <p class="widget-subtitle">Keys to pass automated screening</p>
                </div>
            </div>

            <div class="ats-checklist">
                <div class="ats-item">
                    <div class="ats-check-icon {{ $stats['avg_strength'] >= 50 ? 'done' : '' }}">
                        <i class="fas {{ $stats['avg_strength'] >= 50 ? 'fa-check' : 'fa-circle' }}"></i>
                    </div>
                    <div class="ats-item-content">
                        <strong>Contact Info Complete</strong>
                        <p>Phone, email, and location verified</p>
                    </div>
                </div>

                <div class="ats-item">
                    <div class="ats-check-icon {{ $stats['total_skills'] >= 4 ? 'done' : '' }}">
                        <i class="fas {{ $stats['total_skills'] >= 4 ? 'fa-check' : 'fa-circle' }}"></i>
                    </div>
                    <div class="ats-item-content">
                        <strong>Target Keywords & Skills</strong>
                        <p>At least 4-6 specific technical/hard skills</p>
                    </div>
                </div>

                <div class="ats-item">
                    <div class="ats-check-icon {{ $stats['total_experience'] >= 1 ? 'done' : '' }}">
                        <i class="fas {{ $stats['total_experience'] >= 1 ? 'fa-check' : 'fa-circle' }}"></i>
                    </div>
                    <div class="ats-item-content">
                        <strong>Quantified Experience</strong>
                        <p>Include %, $, or metrics in bullet points</p>
                    </div>
                </div>

                <div class="ats-item">
                    <div class="ats-check-icon done">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="ats-item-content">
                        <strong>ATS-Friendly Structure</strong>
                        <p>Clear section hierarchy & standard fonts</p>
                    </div>
                </div>
            </div>

            <div class="widget-footer-action">
                <button type="button" class="btn btn-secondary btn-sm btn-block" onclick="toggleChatbot(); sendSuggestion('How do I optimize my resume for ATS screening?');">
                    <i class="fas fa-robot"></i> Scan With AI Assistant
                </button>
            </div>
        </div>

        <!-- AI Assistant Fast Prompts Card -->
        <div class="side-widget-card ai-prompts-widget">
            <div class="widget-header">
                <div class="widget-icon-box cyan"><i class="fas fa-magic"></i></div>
                <div>
                    <h4 class="widget-title">AI Career Shortcuts</h4>
                    <p class="widget-subtitle">One-click career writing assist</p>
                </div>
            </div>

            <div class="quick-prompts-list">
                <button type="button" class="prompt-quick-chip" onclick="toggleChatbot(); sendSuggestion('How to write high-impact work experience bullet points?');">
                    <i class="fas fa-bolt"></i> Strong Action Verbs
                </button>
                <button type="button" class="prompt-quick-chip" onclick="toggleChatbot(); sendSuggestion('Give me an example of an impactful professional summary for tech roles.');">
                    <i class="fas fa-pen-nib"></i> Executive Summary Sample
                </button>
                <button type="button" class="prompt-quick-chip" onclick="toggleChatbot(); sendSuggestion('What top in-demand skills should I feature on a 2026 resume?');">
                    <i class="fas fa-chart-pie"></i> High-Demand Skills List
                </button>
                <button type="button" class="prompt-quick-chip" onclick="toggleChatbot(); sendSuggestion('Give me resume format tips');">
                    <i class="fas fa-file-alt"></i> Resume Formatting Guide
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast for Copy Link -->
<div id="dashboard-toast" class="dashboard-toast">
    <i class="fas fa-check-circle toast-icon"></i>
    <span id="toast-message">Preview link copied to clipboard!</span>
</div>
@endsection

@push('scripts')
<script>
// Confirm duplicate
function confirmDuplicate(title) {
    return confirm(`Create a duplicate copy of "${title}"?`);
}

// Copy Preview Link to Clipboard with Toast
function copyPreviewLink(url) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => showToast('Preview link copied to clipboard!'));
    } else {
        const temp = document.createElement('input');
        temp.value = url;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        showToast('Preview link copied to clipboard!');
    }
}

function showToast(message) {
    const toast = document.getElementById('dashboard-toast');
    const msgEl = document.getElementById('toast-message');
    if (!toast) return;
    msgEl.textContent = message;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2800);
}

// Toggle Template Showcase Banner
function toggleTemplateShowcase() {
    const grid = document.getElementById('template-showcase-grid');
    const text = document.getElementById('templates-toggle-text');
    const icon = document.getElementById('templates-toggle-icon');
    if (grid.style.display === 'none') {
        grid.style.display = 'grid';
        text.textContent = 'Hide';
        icon.className = 'fas fa-chevron-up';
        localStorage.setItem('dashboard_showcase_visible', 'true');
    } else {
        grid.style.display = 'none';
        text.textContent = 'Show';
        icon.className = 'fas fa-chevron-down';
        localStorage.setItem('dashboard_showcase_visible', 'false');
    }
}

// Restore showcase preference
if (localStorage.getItem('dashboard_showcase_visible') === 'false') {
    const grid = document.getElementById('template-showcase-grid');
    const text = document.getElementById('templates-toggle-text');
    const icon = document.getElementById('templates-toggle-icon');
    if (grid) {
        grid.style.display = 'none';
        text.textContent = 'Show';
        icon.className = 'fas fa-chevron-down';
    }
}

// View Switcher (Grid vs List)
let currentView = localStorage.getItem('dashboard_view_mode') || 'grid';

function switchView(mode) {
    currentView = mode;
    localStorage.setItem('dashboard_view_mode', mode);

    const gridView = document.getElementById('resumes-grid-view');
    const listView = document.getElementById('resumes-list-view');
    const gridBtn = document.getElementById('view-grid-btn');
    const listBtn = document.getElementById('view-list-btn');

    if (!gridView || !listView) return;

    if (mode === 'list') {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
    } else {
        gridView.style.display = 'grid';
        listView.style.display = 'none';
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
    }
}

// Initialize view
if (currentView === 'list') {
    switchView('list');
}

// Filter and Search System
let activeStatusFilter = 'all';

function setStatusFilter(filter) {
    activeStatusFilter = filter;
    document.querySelectorAll('.filter-tab').forEach(tab => {
        if (tab.getAttribute('data-filter') === filter) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
    filterResumes();
}

function clearSearch() {
    const searchInput = document.getElementById('resume-search');
    if (searchInput) {
        searchInput.value = '';
        document.getElementById('clear-search-btn').style.display = 'none';
        filterResumes();
    }
}

function resetAllFilters() {
    clearSearch();
    document.getElementById('filter-template').value = '';
    setStatusFilter('all');
}

function filterResumes() {
    const query = (document.getElementById('resume-search')?.value || '').toLowerCase().trim();
    const clearBtn = document.getElementById('clear-search-btn');
    if (clearBtn) clearBtn.style.display = query ? 'block' : 'none';

    const selectedTemplate = document.getElementById('filter-template')?.value || '';

    const cards = document.querySelectorAll('.resume-card:not(.add-resume-card)');
    const rows = document.querySelectorAll('.resume-table-row');
    let visibleCount = 0;

    cards.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const name = card.getAttribute('data-name') || '';
        const role = card.getAttribute('data-role') || '';
        const status = card.getAttribute('data-status') || '';
        const template = card.getAttribute('data-template') || '';
        const score = parseInt(card.getAttribute('data-score') || '0', 10);

        // Status match
        let matchesStatus = false;
        if (activeStatusFilter === 'all') matchesStatus = true;
        else if (activeStatusFilter === 'published' && status === 'published') matchesStatus = true;
        else if (activeStatusFilter === 'draft' && status === 'draft') matchesStatus = true;
        else if (activeStatusFilter === 'high-ats' && score >= 80) matchesStatus = true;

        // Template match
        const matchesTemplate = !selectedTemplate || template === selectedTemplate;

        // Search match
        const matchesSearch = !query || title.includes(query) || name.includes(query) || role.includes(query);

        const isVisible = matchesStatus && matchesTemplate && matchesSearch;
        card.style.display = isVisible ? 'block' : 'none';

        if (isVisible) visibleCount++;
    });

    // Also filter table rows
    rows.forEach(row => {
        const title = row.getAttribute('data-title') || '';
        const name = row.getAttribute('data-name') || '';
        const role = row.getAttribute('data-role') || '';
        const status = row.getAttribute('data-status') || '';
        const template = row.getAttribute('data-template') || '';
        const score = parseInt(row.getAttribute('data-score') || '0', 10);

        let matchesStatus = false;
        if (activeStatusFilter === 'all') matchesStatus = true;
        else if (activeStatusFilter === 'published' && status === 'published') matchesStatus = true;
        else if (activeStatusFilter === 'draft' && status === 'draft') matchesStatus = true;
        else if (activeStatusFilter === 'high-ats' && score >= 80) matchesStatus = true;

        const matchesTemplate = !selectedTemplate || template === selectedTemplate;
        const matchesSearch = !query || title.includes(query) || name.includes(query) || role.includes(query);

        row.style.display = (matchesStatus && matchesTemplate && matchesSearch) ? '' : 'none';
    });

    // Handle empty state notice
    const emptyNotice = document.getElementById('no-filter-results');
    const addCard = document.getElementById('add-resume-card');

    if (emptyNotice) {
        if (visibleCount === 0 && cards.length > 0) {
            emptyNotice.style.display = 'block';
            if (addCard) addCard.style.display = 'none';
        } else {
            emptyNotice.style.display = 'none';
            if (addCard) addCard.style.display = '';
        }
    }
}

function sortResumes() {
    const sortBy = document.getElementById('sort-resumes')?.value || 'updated-desc';
    const gridContainer = document.getElementById('resumes-grid-view');
    const tableBody = document.querySelector('.resumes-table tbody');

    if (!gridContainer) return;

    const cards = Array.from(gridContainer.querySelectorAll('.resume-card:not(.add-resume-card)'));
    const addCard = document.getElementById('add-resume-card');

    const sortFn = (a, b) => {
        const titleA = a.getAttribute('data-title');
        const titleB = b.getAttribute('data-title');
        const scoreA = parseInt(a.getAttribute('data-score') || '0', 10);
        const scoreB = parseInt(b.getAttribute('data-score') || '0', 10);
        const updatedA = parseInt(a.getAttribute('data-updated') || '0', 10);
        const updatedB = parseInt(b.getAttribute('data-updated') || '0', 10);

        switch (sortBy) {
            case 'updated-asc': return updatedA - updatedB;
            case 'score-desc':  return scoreB - scoreA;
            case 'score-asc':   return scoreA - scoreB;
            case 'title-asc':   return titleA.localeCompare(titleB);
            case 'title-desc':  return titleB.localeCompare(titleA);
            case 'updated-desc':
            default:            return updatedB - updatedA;
        }
    };

    cards.sort(sortFn);
    cards.forEach(c => gridContainer.appendChild(c));
    if (addCard) gridContainer.appendChild(addCard);

    if (tableBody) {
        const rows = Array.from(tableBody.querySelectorAll('.resume-table-row'));
        rows.sort(sortFn);
        rows.forEach(r => tableBody.appendChild(r));
    }
}
</script>
@endpush
