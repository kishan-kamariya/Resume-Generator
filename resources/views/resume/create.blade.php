@extends('layouts.app')

@section('title', 'Create Resume')
@section('page-title', 'Create New Resume')

@push('scripts')
<script>document.body.classList.add('has-wizard');</script>
@endpush

@section('content')
<div class="wizard-container">
    <!-- Wizard Steps -->
    <div class="wizard-steps" id="wizard-steps">
        <div class="wizard-step active" data-step="0">
            <div class="step-circle">1</div>
            <span class="step-label">Personal</span>
        </div>
        <div class="wizard-step" data-step="1">
            <div class="step-circle">2</div>
            <span class="step-label">Summary</span>
        </div>
        <div class="wizard-step" data-step="2">
            <div class="step-circle">3</div>
            <span class="step-label">Experience</span>
        </div>
        <div class="wizard-step" data-step="3">
            <div class="step-circle">4</div>
            <span class="step-label">Education</span>
        </div>
        <div class="wizard-step" data-step="4">
            <div class="step-circle">5</div>
            <span class="step-label">Skills</span>
        </div>
        <div class="wizard-step" data-step="5">
            <div class="step-circle">6</div>
            <span class="step-label">Projects</span>
        </div>
        <div class="wizard-step" data-step="6">
            <div class="step-circle">7</div>
            <span class="step-label">Certs</span>
        </div>
        <div class="wizard-step" data-step="7">
            <div class="step-circle">8</div>
            <span class="step-label">Languages</span>
        </div>
        <div class="wizard-step" data-step="8">
            <div class="step-circle">9</div>
            <span class="step-label">Template</span>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('resume.store') }}" enctype="multipart/form-data" id="resume-form" autocomplete="off" novalidate>
        @csrf
        <input type="hidden" name="template" id="template-input" value="{{ request('template', 'classic') }}">

        @if ($errors->any())
            <div style="background-color: var(--danger); color: white; padding: 15px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ── STEP 1: Personal Info ── -->
        <div class="wizard-pane active card" id="pane-0">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-user" style="color:var(--accent-primary);margin-right:8px;"></i>Personal Information</h2>
            </div>

            <div class="form-group">
                <label class="form-label" for="r-title">Resume Title <span style="color:var(--danger);">*</span></label>
                <input type="text" name="title" id="r-title" class="form-control" placeholder="e.g., Software Engineer Resume" required value="{{ old('title') }}">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="r-full-name">Full Name <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="full_name" id="r-full-name" class="form-control" placeholder="John Doe" required value="{{ old('full_name') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="r-job-title">Job Title / Profession</label>
                    <input type="text" name="job_title" id="r-job-title" class="form-control" placeholder="e.g., Software Engineer" value="{{ old('job_title') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="r-email">Email Address (@gmail.com) <span style="color:var(--danger);">*</span></label>
                    <input type="email" name="email" id="r-email" class="form-control @error('email') is-invalid @enderror" placeholder="john@gmail.com" required value="{{ old('email') }}">
                    <div class="form-error" id="r-email-error" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> <span id="r-email-error-text"></span>
                    </div>
                    @error('email')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i> <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="r-phone">Phone Number</label>
                    <input type="text" name="phone" id="r-phone" class="form-control" placeholder="+1 234 567 8900" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="r-city">City</label>
                    <input type="text" name="city" id="r-city" class="form-control" placeholder="New York" value="{{ old('city') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="r-country">Country</label>
                    <input type="text" name="country" id="r-country" class="form-control" placeholder="United States" value="{{ old('country') }}">
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label" for="r-linkedin">LinkedIn URL (optional)</label>
                    <input type="text" name="linkedin" id="r-linkedin" class="form-control" placeholder="linkedin.com/in/..." value="{{ old('linkedin') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="r-github">GitHub URL (optional)</label>
                    <input type="text" name="github" id="r-github" class="form-control" placeholder="github.com/..." value="{{ old('github') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="r-website">Website / Portfolio (optional)</label>
                    <input type="text" name="website" id="r-website" class="form-control" placeholder="https://..." value="{{ old('website') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="r-photo">Profile Photo (optional)</label>
                <input type="file" name="profile_photo" id="r-photo" class="form-control" accept="image/*" onchange="previewPhoto(this)" style="padding:8px;">
                <img id="photo-preview" src="" style="display:none;width:80px;height:80px;border-radius:50%;margin-top:10px;object-fit:cover;border:2px solid var(--accent-primary);">
            </div>
        </div>

        <!-- ── STEP 2: Summary ── -->
        <div class="wizard-pane card" id="pane-1">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-align-left" style="color:var(--accent-primary);margin-right:8px;"></i>Professional Summary</h2>
            </div>
            <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:20px;">Write 3–4 sentences highlighting your experience, skills, and career goals. This is the first thing recruiters read!</p>
            <div class="form-group">
                <label class="form-label" for="r-summary">Summary</label>
                <textarea name="summary" id="r-summary" class="form-textarea" rows="6"
                    placeholder="Results-driven Software Engineer with 5+ years of experience building scalable web applications. Skilled in React, Node.js, and AWS. Delivered 40% performance improvements at XYZ Corp. Seeking a senior role to drive product innovation.">{{ old('summary') }}</textarea>
            </div>
            <div style="background:rgba(108,99,255,0.06);border:1px solid rgba(108,99,255,0.15);border-radius:var(--radius-sm);padding:14px;font-size:0.82rem;color:var(--text-secondary);">
                <i class="fas fa-lightbulb" style="color:var(--warning);margin-right:6px;"></i>
                <strong>Tip:</strong> Use the AI chatbot (bottom right) to get personalized help writing your summary!
            </div>
        </div>

        <!-- ── STEP 3: Work Experience ── -->
        <div class="wizard-pane card" id="pane-2">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-briefcase" style="color:var(--accent-primary);margin-right:8px;"></i>Work Experience</h2>
            </div>

            <div id="experience-container">
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">#1 — Most Recent Job</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Company Name <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="work_experience[0][company]" class="form-control" placeholder="Google Inc.">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Your Position <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="work_experience[0][position]" class="form-control" placeholder="Senior Software Engineer">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <input type="text" name="work_experience[0][location]" class="form-control" placeholder="Mountain View, CA">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Date <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="work_experience[0][start_date]" class="form-control" placeholder="Jan 2022">
                            <div class="form-hint"><i class="fas fa-info-circle"></i> Use format: <code>Jan 2022</code> or <code>2022-01</code></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">End Date <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="work_experience[0][end_date]" class="form-control" placeholder="Present or Jan 2024">
                            <div class="form-hint"><i class="fas fa-info-circle"></i> Type "Present" if still working here</div>
                        </div>
                        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;">
                            <label class="form-check">
                                <input type="checkbox" name="work_experience[0][currently_working]" value="1"> Currently working here
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description / Achievements</label>
                        <textarea name="work_experience[0][description]" class="form-textarea" rows="4" placeholder="• Built REST APIs serving 500K daily users&#10;• Led a team of 6 engineers to deliver product on time&#10;• Reduced load times by 40% through optimization"></textarea>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-add-section" onclick="addExperience()">
                <i class="fas fa-plus"></i> Add Another Job
            </button>
        </div>

        <!-- ── STEP 4: Education ── -->
        <div class="wizard-pane card" id="pane-3">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-graduation-cap" style="color:var(--accent-primary);margin-right:8px;"></i>Education</h2>
            </div>

            <div id="education-container">
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">#1</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Institution / University <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="education[0][institution]" class="form-control" placeholder="MIT">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Degree <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="education[0][degree]" class="form-control" placeholder="Bachelor of Science">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Field of Study <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="education[0][field_of_study]" class="form-control" placeholder="Computer Science">
                        </div>
                        <div class="form-group">
                            <label class="form-label">GPA (optional)</label>
                            <input type="number" name="education[0][gpa]" class="form-control" placeholder="3.8" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="text" name="education[0][start_date]" class="form-control" placeholder="Sep 2018">
                            <div class="form-hint"><i class="fas fa-info-circle"></i> e.g. <code>Sep 2018</code></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="text" name="education[0][end_date]" class="form-control" placeholder="May 2022">
                            <div class="form-hint"><i class="fas fa-info-circle"></i> e.g. <code>May 2022</code></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description / Achievements (optional)</label>
                        <textarea name="education[0][description]" class="form-textarea" rows="2" placeholder="Dean's List, Relevant coursework: Data Structures, Algorithms, Machine Learning"></textarea>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-add-section" onclick="addEducation()">
                <i class="fas fa-plus"></i> Add Another Degree
            </button>
        </div>

        <!-- ── STEP 5: Skills ── -->
        <div class="wizard-pane card" id="pane-4">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-tools" style="color:var(--accent-primary);margin-right:8px;"></i>Skills</h2>
            </div>
            <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:20px;">Add your technical and soft skills. Use categories to organize them.</p>

            <div id="skills-container">
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Skill #1</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Skill Name <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="skills[0][name]" class="form-control" placeholder="e.g., React.js">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="skills[0][category]" class="form-select">
                                <option>Technical</option>
                                <option>Soft Skills</option>
                                <option>Tools</option>
                                <option>Frameworks</option>
                                <option>Languages</option>
                                <option>Databases</option>
                                <option>Cloud</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Level</label>
                            <select name="skills[0][level]" class="form-select">
                                <option>Beginner</option>
                                <option selected>Intermediate</option>
                                <option>Advanced</option>
                                <option>Expert</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-add-section" onclick="addSkill()">
                <i class="fas fa-plus"></i> Add Another Skill
            </button>
        </div>

        <!-- ── STEP 6: Projects ── -->
        <div class="wizard-pane card" id="pane-5">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-rocket" style="color:var(--accent-primary);margin-right:8px;"></i>Projects</h2>
            </div>

            <div id="projects-container">
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Project #1</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Project Name <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="projects[0][name]" class="form-control" placeholder="Resume Generator App">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Your Role</label>
                            <input type="text" name="projects[0][role]" class="form-control" placeholder="Full Stack Developer">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Project URL (optional)</label>
                            <input type="text" name="projects[0][url]" class="form-control" placeholder="https://github.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Technologies Used</label>
                            <input type="text" name="projects[0][technologies]" class="form-control" placeholder="Laravel, MySQL, React">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="projects[0][description]" class="form-textarea" rows="3" placeholder="Built a full-stack resume builder with AI chatbot, 3 templates, and PDF export. Serving 200+ users."></textarea>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-add-section" onclick="addProject()">
                <i class="fas fa-plus"></i> Add Another Project
            </button>
        </div>

        <!-- ── STEP 7: Certifications ── -->
        <div class="wizard-pane card" id="pane-6">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-certificate" style="color:var(--accent-primary);margin-right:8px;"></i>Certifications</h2>
            </div>

            <div id="certifications-container">
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Certification #1</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Certification Name <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="certifications[0][name]" class="form-control" placeholder="AWS Solutions Architect">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Issuing Organization <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="certifications[0][issuer]" class="form-control" placeholder="Amazon Web Services">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Issue Date <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="certifications[0][issue_date]" class="form-control" placeholder="March 2024">
                            <div class="form-hint"><i class="fas fa-info-circle"></i> e.g. <code>March 2024</code></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Expiry Date (optional)</label>
                            <input type="text" name="certifications[0][expiry_date]" class="form-control" placeholder="March 2027">
                            <div class="form-hint"><i class="fas fa-info-circle"></i> e.g. <code>March 2027</code></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Credential URL (optional)</label>
                        <input type="text" name="certifications[0][credential_url]" class="form-control" placeholder="https://credly.com/...">
                    </div>
                </div>
            </div>

            <button type="button" class="btn-add-section" onclick="addCertification()">
                <i class="fas fa-plus"></i> Add Another Certification
            </button>
        </div>

        <!-- ── STEP 8: Languages ── -->
        <div class="wizard-pane card" id="pane-7">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-globe" style="color:var(--accent-primary);margin-right:8px;"></i>Languages</h2>
            </div>

            <div id="languages-container">
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Language #1</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Language <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="languages[0][name]" class="form-control" placeholder="English">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Proficiency</label>
                            <select name="languages[0][proficiency]" class="form-select">
                                <option>Elementary</option>
                                <option>Limited Working</option>
                                <option selected>Professional Working</option>
                                <option>Full Professional</option>
                                <option>Native</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-add-section" onclick="addLanguage()">
                <i class="fas fa-plus"></i> Add Another Language
            </button>
        </div>

        <!-- ── STEP 9: Template ── -->
        <div class="wizard-pane card" id="pane-8">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-palette" style="color:var(--accent-primary);margin-right:8px;"></i>Choose Your Template</h2>
            </div>
            <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:20px;">Select the resume design that best fits your industry and style.</p>

            <div class="template-grid">
                @php $currentTpl = request('template', 'classic'); @endphp
                <div class="template-option {{ $currentTpl === 'classic' ? 'selected' : '' }}" data-value="classic" onclick="selectTemplate('classic')" id="tpl-classic">
                    <div class="template-preview-box template-preview-classic">🏛️</div>
                    <div class="template-label">Classic</div>
                </div>
                <div class="template-option {{ $currentTpl === 'modern' ? 'selected' : '' }}" data-value="modern" onclick="selectTemplate('modern')" id="tpl-modern">
                    <div class="template-preview-box template-preview-modern">🎨</div>
                    <div class="template-label">Modern</div>
                </div>
                <div class="template-option {{ $currentTpl === 'minimal' ? 'selected' : '' }}" data-value="minimal" onclick="selectTemplate('minimal')" id="tpl-minimal">
                    <div class="template-preview-box template-preview-minimal">✨</div>
                    <div class="template-label">Minimal</div>
                </div>
            </div>

            <div style="margin-top:28px;padding:16px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.2);border-radius:var(--radius-sm);">
                <label class="form-check" for="save-draft">
                    <input type="checkbox" id="save-draft" name="save_draft"> Save as Draft (you can continue editing later)
                </label>
            </div>
        </div>

        <!-- Wizard Nav -->
        <div class="wizard-nav" id="resume-wizard">
            <button type="button" class="btn btn-secondary" id="wizard-prev" onclick="prevStep()" style="display:none;">
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            <button type="button" class="btn btn-primary" id="wizard-next" onclick="nextStep()">
                Next <i class="fas fa-arrow-right"></i>
            </button>
            <button type="submit" class="btn btn-primary btn-lg" id="wizard-submit" style="display:none;">
                <i class="fas fa-check"></i> Create Resume
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let expCount  = 1;
let eduCount  = 1;
let skillCount = 1;
let projCount = 1;
let certCount = 1;
let langCount = 1;

function addExperience() {
    const c = document.getElementById('experience-container');
    const i = expCount++;
    const div = document.createElement('div');
    div.className = 'section-block';
    div.innerHTML = `
        <div class="section-block-header">
            <span class="section-block-title">#${i+1}</span>
            <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Company Name</label><input type="text" name="work_experience[${i}][company]" class="form-control" placeholder="Company Name"></div>
            <div class="form-group"><label class="form-label">Your Position</label><input type="text" name="work_experience[${i}][position]" class="form-control" placeholder="Position"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Location</label><input type="text" name="work_experience[${i}][location]" class="form-control" placeholder="City, Country"></div>
            <div class="form-group"><label class="form-label">Start Date</label><input type="text" name="work_experience[${i}][start_date]" class="form-control" placeholder="Jan 2020"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">End Date</label><input type="text" name="work_experience[${i}][end_date]" class="form-control" placeholder="Present"></div>
            <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;"><label class="form-check"><input type="checkbox" name="work_experience[${i}][currently_working]" value="1"> Currently working here</label></div>
        </div>
        <div class="form-group"><label class="form-label">Description / Achievements</label><textarea name="work_experience[${i}][description]" class="form-textarea" rows="4" placeholder="• Key achievements and responsibilities"></textarea></div>
    `;
    c.appendChild(div);
}

function addEducation() {
    const c = document.getElementById('education-container');
    const i = eduCount++;
    const div = document.createElement('div');
    div.className = 'section-block';
    div.innerHTML = `
        <div class="section-block-header">
            <span class="section-block-title">#${i+1}</span>
            <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Institution</label><input type="text" name="education[${i}][institution]" class="form-control" placeholder="University Name"></div>
            <div class="form-group"><label class="form-label">Degree</label><input type="text" name="education[${i}][degree]" class="form-control" placeholder="Bachelor of Science"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Field of Study</label><input type="text" name="education[${i}][field_of_study]" class="form-control" placeholder="Computer Science"></div>
            <div class="form-group"><label class="form-label">GPA</label><input type="number" name="education[${i}][gpa]" class="form-control" placeholder="3.8" step="0.01" min="0"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Start Date</label><input type="text" name="education[${i}][start_date]" class="form-control" placeholder="Sep 2018"></div>
            <div class="form-group"><label class="form-label">End Date</label><input type="text" name="education[${i}][end_date]" class="form-control" placeholder="May 2022"></div>
        </div>
    `;
    c.appendChild(div);
}

function addSkill() {
    const c = document.getElementById('skills-container');
    const i = skillCount++;
    const div = document.createElement('div');
    div.className = 'section-block';
    div.innerHTML = `
        <div class="section-block-header">
            <span class="section-block-title">Skill #${i+1}</span>
            <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i></button>
        </div>
        <div class="form-row-3">
            <div class="form-group"><label class="form-label">Skill Name</label><input type="text" name="skills[${i}][name]" class="form-control" placeholder="e.g., Python"></div>
            <div class="form-group"><label class="form-label">Category</label>
                <select name="skills[${i}][category]" class="form-select">
                    <option>Technical</option><option>Soft Skills</option><option>Tools</option><option>Frameworks</option><option>Languages</option><option>Databases</option><option>Cloud</option><option>Other</option>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Level</label>
                <select name="skills[${i}][level]" class="form-select">
                    <option>Beginner</option><option selected>Intermediate</option><option>Advanced</option><option>Expert</option>
                </select>
            </div>
        </div>
    `;
    c.appendChild(div);
}

function addProject() {
    const c = document.getElementById('projects-container');
    const i = projCount++;
    const div = document.createElement('div');
    div.className = 'section-block';
    div.innerHTML = `
        <div class="section-block-header">
            <span class="section-block-title">Project #${i+1}</span>
            <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Project Name</label><input type="text" name="projects[${i}][name]" class="form-control" placeholder="Project Name"></div>
            <div class="form-group"><label class="form-label">Your Role</label><input type="text" name="projects[${i}][role]" class="form-control" placeholder="Full Stack Developer"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Project URL</label><input type="text" name="projects[${i}][url]" class="form-control" placeholder="https://github.com/..."></div>
            <div class="form-group"><label class="form-label">Technologies</label><input type="text" name="projects[${i}][technologies]" class="form-control" placeholder="Laravel, Vue.js, MySQL"></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="projects[${i}][description]" class="form-textarea" rows="3" placeholder="Describe what you built and its impact"></textarea></div>
    `;
    c.appendChild(div);
}

function addCertification() {
    const c = document.getElementById('certifications-container');
    const i = certCount++;
    const div = document.createElement('div');
    div.className = 'section-block';
    div.innerHTML = `
        <div class="section-block-header">
            <span class="section-block-title">Certification #${i+1}</span>
            <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Certification Name</label><input type="text" name="certifications[${i}][name]" class="form-control" placeholder="Certification Name"></div>
            <div class="form-group"><label class="form-label">Issuing Organization</label><input type="text" name="certifications[${i}][issuer]" class="form-control" placeholder="Issuer"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Issue Date</label><input type="text" name="certifications[${i}][issue_date]" class="form-control" placeholder="Jan 2024"></div>
            <div class="form-group"><label class="form-label">Expiry Date</label><input type="text" name="certifications[${i}][expiry_date]" class="form-control" placeholder="Jan 2027"></div>
        </div>
        <div class="form-group"><label class="form-label">Credential URL</label><input type="text" name="certifications[${i}][credential_url]" class="form-control" placeholder="https://..."></div>
    `;
    c.appendChild(div);
}

function addLanguage() {
    const c = document.getElementById('languages-container');
    const i = langCount++;
    const div = document.createElement('div');
    div.className = 'section-block';
    div.innerHTML = `
        <div class="section-block-header">
            <span class="section-block-title">Language #${i+1}</span>
            <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Language</label><input type="text" name="languages[${i}][name]" class="form-control" placeholder="Spanish"></div>
            <div class="form-group"><label class="form-label">Proficiency</label>
                <select name="languages[${i}][proficiency]" class="form-select">
                    <option>Elementary</option><option>Limited Working</option><option selected>Professional Working</option><option>Full Professional</option><option>Native</option>
                </select>
            </div>
        </div>
    `;
    c.appendChild(div);
}
</script>
@endpush
