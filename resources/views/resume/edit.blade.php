@extends('layouts.app')

@section('title', 'Edit Resume')
@section('page-title', 'Edit Resume')

@section('content')
<div class="wizard-container">
    <!-- Wizard Steps -->
    <div class="wizard-steps" id="wizard-steps">
        <div class="wizard-step active" data-step="0"><div class="step-circle">1</div><span class="step-label">Personal</span></div>
        <div class="wizard-step" data-step="1"><div class="step-circle">2</div><span class="step-label">Summary</span></div>
        <div class="wizard-step" data-step="2"><div class="step-circle">3</div><span class="step-label">Experience</span></div>
        <div class="wizard-step" data-step="3"><div class="step-circle">4</div><span class="step-label">Education</span></div>
        <div class="wizard-step" data-step="4"><div class="step-circle">5</div><span class="step-label">Skills</span></div>
        <div class="wizard-step" data-step="5"><div class="step-circle">6</div><span class="step-label">Projects</span></div>
        <div class="wizard-step" data-step="6"><div class="step-circle">7</div><span class="step-label">Certs</span></div>
        <div class="wizard-step" data-step="7"><div class="step-circle">8</div><span class="step-label">Languages</span></div>
        <div class="wizard-step" data-step="8"><div class="step-circle">9</div><span class="step-label">Template</span></div>
    </div>

    <form method="POST" action="{{ route('resume.update', $resume->id) }}" enctype="multipart/form-data" id="resume-form" novalidate>
        @csrf @method('PUT')
        <input type="hidden" name="template" id="template-input" value="{{ $resume->template }}">

        @if ($errors->any())
            <div style="background-color: var(--danger); color: white; padding: 15px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Step 1: Personal -->
        <div class="wizard-pane active card" id="pane-0">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-user" style="color:var(--accent-primary);margin-right:8px;"></i>Personal Information</h2>
            </div>
            <div class="form-group">
                <label class="form-label">Resume Title *</label>
                <input type="text" name="title" class="form-control" required value="{{ $resume->title }}">
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Full Name *</label><input type="text" name="full_name" class="form-control" required value="{{ $resume->full_name }}"></div>
                <div class="form-group"><label class="form-label">Job Title / Profession</label><input type="text" name="job_title" class="form-control" value="{{ $resume->job_title }}"></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email Address (@gmail.com)<span style="color:var(--danger);">*</span></label>
                    <input type="email" name="email" id="r-email" class="form-control @error('email') is-invalid @enderror" placeholder="example@gmail.com" required value="{{ $resume->email }}">
                    <div class="form-error" id="r-email-error" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> <span id="r-email-error-text"></span>
                    </div>
                    @error('email')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i> <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ $resume->phone }}"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ $resume->city }}"></div>
                <div class="form-group"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ $resume->country }}"></div>
            </div>
            <div class="form-row-3">
                <div class="form-group"><label class="form-label">LinkedIn (optional)</label><input type="text" name="linkedin" class="form-control" value="{{ $resume->linkedin }}"></div>
                <div class="form-group"><label class="form-label">GitHub (optional)</label><input type="text" name="github" class="form-control" value="{{ $resume->github }}"></div>
                <div class="form-group"><label class="form-label">Website / Portfolio (optional)</label><input type="text" name="website" class="form-control" value="{{ $resume->website }}"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Profile Photo (optional)</label>
                @if($resume->profile_photo)
                    <div style="margin-bottom:10px;"><img src="{{ Storage::url($resume->profile_photo) }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--accent-primary);"></div>
                @endif
                <input type="file" name="profile_photo" class="form-control" accept="image/*" onchange="previewPhoto(this)" style="padding:8px;">
                <img id="photo-preview" src="" style="display:none;width:80px;height:80px;border-radius:50%;margin-top:10px;object-fit:cover;border:2px solid var(--accent-primary);">
            </div>
        </div>

        <!-- Step 2: Summary -->
        <div class="wizard-pane card" id="pane-1">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-align-left" style="color:var(--accent-primary);margin-right:8px;"></i>Professional Summary</h2>
            </div>
            <div class="form-group">
                <label class="form-label">Summary</label>
                <textarea name="summary" class="form-textarea" rows="6">{{ $resume->summary }}</textarea>
            </div>
        </div>

        <!-- Step 3: Work Experience -->
        <div class="wizard-pane card" id="pane-2">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-briefcase" style="color:var(--accent-primary);margin-right:8px;"></i>Work Experience</h2>
            </div>
            <div id="experience-container">
                @forelse($resume->workExperiences as $i => $exp)
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">#{{ $i+1 }}</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Company</label><input type="text" name="work_experience[{{ $i }}][company]" class="form-control" value="{{ $exp->company }}"></div>
                        <div class="form-group"><label class="form-label">Position</label><input type="text" name="work_experience[{{ $i }}][position]" class="form-control" value="{{ $exp->position }}"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Location</label><input type="text" name="work_experience[{{ $i }}][location]" class="form-control" value="{{ $exp->location }}"></div>
                        <div class="form-group"><label class="form-label">Start Date</label><input type="text" name="work_experience[{{ $i }}][start_date]" class="form-control" value="{{ $exp->start_date }}"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">End Date</label><input type="text" name="work_experience[{{ $i }}][end_date]" class="form-control" value="{{ $exp->end_date }}"></div>
                        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;"><label class="form-check"><input type="checkbox" name="work_experience[{{ $i }}][currently_working]" value="1" {{ $exp->currently_working ? 'checked' : '' }}> Currently working here</label></div>
                    </div>
                    <div class="form-group"><label class="form-label">Description</label><textarea name="work_experience[{{ $i }}][description]" class="form-textarea" rows="4">{{ $exp->description }}</textarea></div>
                </div>
                @empty
                <p style="color:var(--text-muted);font-size:0.88rem;">No work experience added yet.</p>
                @endforelse
            </div>
            <button type="button" class="btn-add-section" onclick="addExperience()"><i class="fas fa-plus"></i> Add Job</button>
        </div>

        <!-- Step 4: Education -->
        <div class="wizard-pane card" id="pane-3">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-graduation-cap" style="color:var(--accent-primary);margin-right:8px;"></i>Education</h2>
            </div>
            <div id="education-container">
                @forelse($resume->educations as $i => $edu)
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">#{{ $i+1 }}</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Institution</label><input type="text" name="education[{{ $i }}][institution]" class="form-control" value="{{ $edu->institution }}"></div>
                        <div class="form-group"><label class="form-label">Degree</label><input type="text" name="education[{{ $i }}][degree]" class="form-control" value="{{ $edu->degree }}"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Field of Study</label><input type="text" name="education[{{ $i }}][field_of_study]" class="form-control" value="{{ $edu->field_of_study }}"></div>
                        <div class="form-group"><label class="form-label">GPA</label><input type="number" name="education[{{ $i }}][gpa]" class="form-control" step="0.01" value="{{ $edu->gpa }}"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Start Date</label><input type="text" name="education[{{ $i }}][start_date]" class="form-control" value="{{ $edu->start_date }}"></div>
                        <div class="form-group"><label class="form-label">End Date</label><input type="text" name="education[{{ $i }}][end_date]" class="form-control" value="{{ $edu->end_date }}"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Description</label><textarea name="education[{{ $i }}][description]" class="form-textarea" rows="2">{{ $edu->description }}</textarea></div>
                </div>
                @empty
                <p style="color:var(--text-muted);font-size:0.88rem;">No education added yet.</p>
                @endforelse
            </div>
            <button type="button" class="btn-add-section" onclick="addEducation()"><i class="fas fa-plus"></i> Add Education</button>
        </div>

        <!-- Step 5: Skills -->
        <div class="wizard-pane card" id="pane-4">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-tools" style="color:var(--accent-primary);margin-right:8px;"></i>Skills</h2>
            </div>
            <div id="skills-container">
                @forelse($resume->skills as $i => $skill)
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Skill #{{ $i+1 }}</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <div class="form-row-3">
                        <div class="form-group"><label class="form-label">Skill Name</label><input type="text" name="skills[{{ $i }}][name]" class="form-control" value="{{ $skill->name }}"></div>
                        <div class="form-group"><label class="form-label">Category</label>
                            <select name="skills[{{ $i }}][category]" class="form-select">
                                @foreach(['Technical','Soft Skills','Tools','Frameworks','Languages','Databases','Cloud','Other'] as $cat)
                                <option {{ $skill->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Level</label>
                            <select name="skills[{{ $i }}][level]" class="form-select">
                                @foreach(['Beginner','Intermediate','Advanced','Expert'] as $lvl)
                                <option {{ $skill->level === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @empty
                <p style="color:var(--text-muted);font-size:0.88rem;">No skills added yet.</p>
                @endforelse
            </div>
            <button type="button" class="btn-add-section" onclick="addSkill()"><i class="fas fa-plus"></i> Add Skill</button>
        </div>

        <!-- Step 6: Projects -->
        <div class="wizard-pane card" id="pane-5">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-rocket" style="color:var(--accent-primary);margin-right:8px;"></i>Projects</h2>
            </div>
            <div id="projects-container">
                @forelse($resume->projects as $i => $project)
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Project #{{ $i+1 }}</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Name</label><input type="text" name="projects[{{ $i }}][name]" class="form-control" value="{{ $project->name }}"></div>
                        <div class="form-group"><label class="form-label">Role</label><input type="text" name="projects[{{ $i }}][role]" class="form-control" value="{{ $project->role }}"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">URL</label><input type="text" name="projects[{{ $i }}][url]" class="form-control" value="{{ $project->url }}"></div>
                        <div class="form-group"><label class="form-label">Technologies</label><input type="text" name="projects[{{ $i }}][technologies]" class="form-control" value="{{ $project->technologies }}"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Description</label><textarea name="projects[{{ $i }}][description]" class="form-textarea" rows="3">{{ $project->description }}</textarea></div>
                </div>
                @empty
                <p style="color:var(--text-muted);font-size:0.88rem;">No projects added yet.</p>
                @endforelse
            </div>
            <button type="button" class="btn-add-section" onclick="addProject()"><i class="fas fa-plus"></i> Add Project</button>
        </div>

        <!-- Step 7: Certifications -->
        <div class="wizard-pane card" id="pane-6">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-certificate" style="color:var(--accent-primary);margin-right:8px;"></i>Certifications</h2>
            </div>
            <div id="certifications-container">
                @forelse($resume->certifications as $i => $cert)
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Cert #{{ $i+1 }}</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Name</label><input type="text" name="certifications[{{ $i }}][name]" class="form-control" value="{{ $cert->name }}"></div>
                        <div class="form-group"><label class="form-label">Issuer</label><input type="text" name="certifications[{{ $i }}][issuer]" class="form-control" value="{{ $cert->issuer }}"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Issue Date</label><input type="text" name="certifications[{{ $i }}][issue_date]" class="form-control" value="{{ $cert->issue_date }}"></div>
                        <div class="form-group"><label class="form-label">Expiry Date</label><input type="text" name="certifications[{{ $i }}][expiry_date]" class="form-control" value="{{ $cert->expiry_date }}"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Credential URL</label><input type="text" name="certifications[{{ $i }}][credential_url]" class="form-control" value="{{ $cert->credential_url }}"></div>
                </div>
                @empty
                <p style="color:var(--text-muted);font-size:0.88rem;">No certifications added yet.</p>
                @endforelse
            </div>
            <button type="button" class="btn-add-section" onclick="addCertification()"><i class="fas fa-plus"></i> Add Certification</button>
        </div>

        <!-- Step 8: Languages -->
        <div class="wizard-pane card" id="pane-7">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-globe" style="color:var(--accent-primary);margin-right:8px;"></i>Languages</h2>
            </div>
            <div id="languages-container">
                @forelse($resume->languages as $i => $lang)
                <div class="section-block">
                    <div class="section-block-header">
                        <span class="section-block-title">Language #{{ $i+1 }}</span>
                        <button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Language</label><input type="text" name="languages[{{ $i }}][name]" class="form-control" value="{{ $lang->name }}"></div>
                        <div class="form-group"><label class="form-label">Proficiency</label>
                            <select name="languages[{{ $i }}][proficiency]" class="form-select">
                                @foreach(['Elementary','Limited Working','Professional Working','Full Professional','Native'] as $prof)
                                <option {{ $lang->proficiency === $prof ? 'selected' : '' }}>{{ $prof }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @empty
                <p style="color:var(--text-muted);font-size:0.88rem;">No languages added yet.</p>
                @endforelse
            </div>
            <button type="button" class="btn-add-section" onclick="addLanguage()"><i class="fas fa-plus"></i> Add Language</button>
        </div>

        <!-- Step 9: Template -->
        <div class="wizard-pane card" id="pane-8">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-palette" style="color:var(--accent-primary);margin-right:8px;"></i>Choose Template</h2>
            </div>
            <div class="template-grid">
                <div class="template-option {{ $resume->template === 'classic' ? 'selected' : '' }}" data-value="classic" onclick="selectTemplate('classic')" id="tpl-classic">
                    <div class="template-preview-box template-preview-classic">🏛️</div>
                    <div class="template-label">Classic</div>
                </div>
                <div class="template-option {{ $resume->template === 'modern' ? 'selected' : '' }}" data-value="modern" onclick="selectTemplate('modern')" id="tpl-modern">
                    <div class="template-preview-box template-preview-modern">🎨</div>
                    <div class="template-label">Modern</div>
                </div>
                <div class="template-option {{ $resume->template === 'minimal' ? 'selected' : '' }}" data-value="minimal" onclick="selectTemplate('minimal')" id="tpl-minimal">
                    <div class="template-preview-box template-preview-minimal">✨</div>
                    <div class="template-label">Minimal</div>
                </div>
            </div>
            <div style="margin-top:28px;padding:16px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.2);border-radius:var(--radius-sm);">
                <label class="form-check">
                    <input type="checkbox" name="save_draft" {{ $resume->is_draft ? 'checked' : '' }}> Keep as Draft
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
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let expCount  = {{ $resume->workExperiences->count() }};
let eduCount  = {{ $resume->educations->count() }};
let skillCount = {{ $resume->skills->count() }};
let projCount = {{ $resume->projects->count() }};
let certCount = {{ $resume->certifications->count() }};
let langCount = {{ $resume->languages->count() }};

function addExperience() {
    const c = document.getElementById('experience-container');
    const i = expCount++;
    const div = document.createElement('div');
    div.className = 'section-block';
    div.innerHTML = `<div class="section-block-header"><span class="section-block-title">#${i+1}</span><button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Company</label><input type="text" name="work_experience[${i}][company]" class="form-control"></div><div class="form-group"><label class="form-label">Position</label><input type="text" name="work_experience[${i}][position]" class="form-control"></div></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Location</label><input type="text" name="work_experience[${i}][location]" class="form-control"></div><div class="form-group"><label class="form-label">Start Date</label><input type="text" name="work_experience[${i}][start_date]" class="form-control"></div></div>
        <div class="form-row"><div class="form-group"><label class="form-label">End Date</label><input type="text" name="work_experience[${i}][end_date]" class="form-control"></div><div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;"><label class="form-check"><input type="checkbox" name="work_experience[${i}][currently_working]" value="1"> Currently working here</label></div></div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="work_experience[${i}][description]" class="form-textarea" rows="4"></textarea></div>`;
    c.appendChild(div);
}
function addEducation() {
    const c = document.getElementById('education-container'); const i = eduCount++;
    const div = document.createElement('div'); div.className = 'section-block';
    div.innerHTML = `<div class="section-block-header"><span class="section-block-title">#${i+1}</span><button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Institution</label><input type="text" name="education[${i}][institution]" class="form-control"></div><div class="form-group"><label class="form-label">Degree</label><input type="text" name="education[${i}][degree]" class="form-control"></div></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Field of Study</label><input type="text" name="education[${i}][field_of_study]" class="form-control"></div><div class="form-group"><label class="form-label">GPA</label><input type="number" name="education[${i}][gpa]" class="form-control" step="0.01"></div></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Start Date</label><input type="text" name="education[${i}][start_date]" class="form-control"></div><div class="form-group"><label class="form-label">End Date</label><input type="text" name="education[${i}][end_date]" class="form-control"></div></div>`;
    c.appendChild(div);
}
function addSkill() {
    const c = document.getElementById('skills-container'); const i = skillCount++;
    const div = document.createElement('div'); div.className = 'section-block';
    div.innerHTML = `<div class="section-block-header"><span class="section-block-title">Skill #${i+1}</span><button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i></button></div>
        <div class="form-row-3"><div class="form-group"><label class="form-label">Name</label><input type="text" name="skills[${i}][name]" class="form-control"></div>
        <div class="form-group"><label class="form-label">Category</label><select name="skills[${i}][category]" class="form-select"><option>Technical</option><option>Soft Skills</option><option>Tools</option><option>Frameworks</option><option>Languages</option><option>Databases</option><option>Cloud</option><option>Other</option></select></div>
        <div class="form-group"><label class="form-label">Level</label><select name="skills[${i}][level]" class="form-select"><option>Beginner</option><option selected>Intermediate</option><option>Advanced</option><option>Expert</option></select></div></div>`;
    c.appendChild(div);
}
function addProject() {
    const c = document.getElementById('projects-container'); const i = projCount++;
    const div = document.createElement('div'); div.className = 'section-block';
    div.innerHTML = `<div class="section-block-header"><span class="section-block-title">Project #${i+1}</span><button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Name</label><input type="text" name="projects[${i}][name]" class="form-control"></div><div class="form-group"><label class="form-label">Role</label><input type="text" name="projects[${i}][role]" class="form-control"></div></div>
        <div class="form-row"><div class="form-group"><label class="form-label">URL</label><input type="text" name="projects[${i}][url]" class="form-control"></div><div class="form-group"><label class="form-label">Technologies</label><input type="text" name="projects[${i}][technologies]" class="form-control"></div></div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="projects[${i}][description]" class="form-textarea" rows="3"></textarea></div>`;
    c.appendChild(div);
}
function addCertification() {
    const c = document.getElementById('certifications-container'); const i = certCount++;
    const div = document.createElement('div'); div.className = 'section-block';
    div.innerHTML = `<div class="section-block-header"><span class="section-block-title">Cert #${i+1}</span><button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Name</label><input type="text" name="certifications[${i}][name]" class="form-control"></div><div class="form-group"><label class="form-label">Issuer</label><input type="text" name="certifications[${i}][issuer]" class="form-control"></div></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Issue Date</label><input type="text" name="certifications[${i}][issue_date]" class="form-control"></div><div class="form-group"><label class="form-label">Expiry Date</label><input type="text" name="certifications[${i}][expiry_date]" class="form-control"></div></div>
        <div class="form-group"><label class="form-label">Credential URL</label><input type="text" name="certifications[${i}][credential_url]" class="form-control"></div>`;
    c.appendChild(div);
}
function addLanguage() {
    const c = document.getElementById('languages-container'); const i = langCount++;
    const div = document.createElement('div'); div.className = 'section-block';
    div.innerHTML = `<div class="section-block-header"><span class="section-block-title">Language #${i+1}</span><button type="button" class="btn-remove-section" onclick="removeSection(this)"><i class="fas fa-trash-alt"></i> Remove</button></div>
        <div class="form-row"><div class="form-group"><label class="form-label">Language</label><input type="text" name="languages[${i}][name]" class="form-control"></div>
        <div class="form-group"><label class="form-label">Proficiency</label><select name="languages[${i}][proficiency]" class="form-select"><option>Elementary</option><option>Limited Working</option><option selected>Professional Working</option><option>Full Professional</option><option>Native</option></select></div></div>`;
    c.appendChild(div);
}
</script>
@endpush
