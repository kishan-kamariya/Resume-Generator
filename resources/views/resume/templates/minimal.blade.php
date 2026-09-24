<div class="resume-doc resume-minimal">
    <!-- Header -->
    <div class="r-name">{{ $resume->full_name ?? 'Your Name' }}</div>
    @if($resume->job_title)
    <div class="r-title">{{ $resume->job_title }}</div>
    @endif
    <div class="r-contact">
        @if($resume->email)<span>{{ $resume->email }}</span>@endif
        @if($resume->phone)<span>{{ $resume->phone }}</span>@endif
        @if($resume->city || $resume->country)<span>{{ collect([$resume->city, $resume->country])->filter()->implode(', ') }}</span>@endif
        @if($resume->linkedin)<span>{{ $resume->linkedin }}</span>@endif
        @if($resume->github)<span>{{ $resume->github }}</span>@endif
        @if($resume->website)<span>{{ $resume->website }}</span>@endif
    </div>

    <!-- Summary -->
    @if($resume->summary)
    <div class="r-section">
        <div class="r-section-title">Summary</div>
        <p style="font-size:12px;color:#334155;line-height:1.7;">{{ $resume->summary }}</p>
    </div>
    @endif

    <!-- Work Experience -->
    @if($resume->workExperiences->isNotEmpty())
    <div class="r-section">
        <div class="r-section-title">Experience</div>
        @foreach($resume->workExperiences as $exp)
        <div class="r-item">
            <div class="r-item-header">
                <span class="r-item-title">{{ $exp->position }}</span>
                <span class="r-item-date">{{ $exp->start_date }}{{ $exp->currently_working ? ' – Present' : ($exp->end_date ? ' – '.$exp->end_date : '') }}</span>
            </div>
            <div class="r-item-sub">{{ $exp->company }}@if($exp->location) · {{ $exp->location }}@endif</div>
            @if($exp->description)<div class="r-item-desc">{{ $exp->description }}</div>@endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Education -->
    @if($resume->educations->isNotEmpty())
    <div class="r-section">
        <div class="r-section-title">Education</div>
        @foreach($resume->educations as $edu)
        <div class="r-item">
            <div class="r-item-header">
                <span class="r-item-title">{{ $edu->degree }}@if($edu->field_of_study), {{ $edu->field_of_study }}@endif</span>
                <span class="r-item-date">{{ $edu->start_date }}{{ $edu->end_date ? ' – '.$edu->end_date : '' }}</span>
            </div>
            <div class="r-item-sub">{{ $edu->institution }}@if($edu->gpa) · GPA: {{ $edu->gpa }}@endif</div>
            @if($edu->description)<div class="r-item-desc">{{ $edu->description }}</div>@endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Skills -->
    @if($resume->skills->isNotEmpty())
    <div class="r-section">
        <div class="r-section-title">Skills</div>
        <div class="r-skills-wrap">
            @foreach($resume->skills as $skill)
            <span class="r-skill-chip">{{ $skill->name }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Projects -->
    @if($resume->projects->isNotEmpty())
    <div class="r-section">
        <div class="r-section-title">Projects</div>
        @foreach($resume->projects as $project)
        <div class="r-item">
            <div class="r-item-header">
                <span class="r-item-title">{{ $project->name }}</span>
                @if($project->url)<span style="font-size:11px;color:#6c63ff;">{{ $project->url }}</span>@endif
            </div>
            @if($project->technologies)<div class="r-item-sub">{{ $project->technologies }}</div>@endif
            @if($project->description)<div class="r-item-desc">{{ $project->description }}</div>@endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Certifications -->
    @if($resume->certifications->isNotEmpty())
    <div class="r-section">
        <div class="r-section-title">Certifications</div>
        @foreach($resume->certifications as $cert)
        <div class="r-item">
            <div class="r-item-header">
                <span class="r-item-title">{{ $cert->name }}</span>
                <span class="r-item-date">{{ $cert->issue_date }}</span>
            </div>
            <div class="r-item-sub">{{ $cert->issuer }}</div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Languages -->
    @if($resume->languages->isNotEmpty())
    <div class="r-section">
        <div class="r-section-title">Languages</div>
        <div class="r-skills-wrap">
            @foreach($resume->languages as $lang)
            <span class="r-skill-chip">{{ $lang->name }} ({{ $lang->proficiency }})</span>
            @endforeach
        </div>
    </div>
    @endif
</div>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
