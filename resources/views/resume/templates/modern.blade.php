<div class="resume-doc resume-modern">
    <!-- Left Sidebar -->
    <div class="r-left">
        <!-- Photo -->
        <div class="r-photo">
            @if($resume->profile_photo)
                <img src="{{ Storage::url($resume->profile_photo) }}" alt="{{ $resume->full_name }}">
            @else
                {{ strtoupper(substr($resume->full_name ?? 'R', 0, 1)) }}
            @endif
        </div>

        <div class="r-name">{{ $resume->full_name ?? 'Your Name' }}</div>
        @if($resume->job_title)
        <div class="r-title">{{ $resume->job_title }}</div>
        @endif

        <!-- Contact -->
        <div class="r-left-section">
            <div class="r-left-title">Contact</div>
            @if($resume->email)<div class="r-contact-item"><i class="fas fa-envelope" style="width:14px;flex-shrink:0;"></i>{{ $resume->email }}</div>@endif
            @if($resume->phone)<div class="r-contact-item"><i class="fas fa-phone" style="width:14px;flex-shrink:0;"></i>{{ $resume->phone }}</div>@endif
            @if($resume->city || $resume->country)<div class="r-contact-item"><i class="fas fa-map-marker-alt" style="width:14px;flex-shrink:0;"></i>{{ collect([$resume->city, $resume->country])->filter()->implode(', ') }}</div>@endif
            @if($resume->linkedin)<div class="r-contact-item"><i class="fab fa-linkedin" style="width:14px;flex-shrink:0;"></i>{{ $resume->linkedin }}</div>@endif
            @if($resume->github)<div class="r-contact-item"><i class="fab fa-github" style="width:14px;flex-shrink:0;"></i>{{ $resume->github }}</div>@endif
            @if($resume->website)<div class="r-contact-item"><i class="fas fa-globe" style="width:14px;flex-shrink:0;"></i>{{ $resume->website }}</div>@endif
        </div>

        <!-- Skills -->
        @if($resume->skills->isNotEmpty())
        <div class="r-left-section">
            <div class="r-left-title">Skills</div>
            @php
            $levelMap = ['Beginner' => '25%', 'Intermediate' => '55%', 'Advanced' => '80%', 'Expert' => '100%'];
            @endphp
            @foreach($resume->skills as $skill)
            <div class="r-skill-item">
                {{ $skill->name }}
                <div class="r-skill-bar">
                    <div class="r-skill-fill" style="width: {{ $levelMap[$skill->level] ?? '60%' }};"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Languages -->
        @if($resume->languages->isNotEmpty())
        <div class="r-left-section">
            <div class="r-left-title">Languages</div>
            @foreach($resume->languages as $lang)
            <div class="r-skill-item">{{ $lang->name }} <span style="opacity:0.65;font-size:10px;">({{ $lang->proficiency }})</span></div>
            @endforeach
        </div>
        @endif

        <!-- Certifications -->
        @if($resume->certifications->isNotEmpty())
        <div class="r-left-section">
            <div class="r-left-title">Certifications</div>
            @foreach($resume->certifications as $cert)
            <div class="r-skill-item" style="margin-bottom:8px;">
                <div style="font-size:11px;font-weight:600;">{{ $cert->name }}</div>
                <div style="font-size:10px;opacity:0.7;">{{ $cert->issuer }} · {{ $cert->issue_date }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Right Content -->
    <div class="r-right">
        <!-- Summary -->
        @if($resume->summary)
        <div class="r-section">
            <div class="r-section-title">About Me</div>
            <p style="font-size:12px;color:#444;line-height:1.65;">{{ $resume->summary }}</p>
        </div>
        @endif

        <!-- Work Experience -->
        @if($resume->workExperiences->isNotEmpty())
        <div class="r-section">
            <div class="r-section-title">Work Experience</div>
            @foreach($resume->workExperiences as $exp)
            <div class="r-item">
                <div class="r-item-title">{{ $exp->position }}</div>
                <div class="r-item-sub">{{ $exp->company }}@if($exp->location) · {{ $exp->location }}@endif</div>
                <div class="r-item-date">
                    {{ $exp->start_date }}{{ $exp->currently_working ? ' – Present' : ($exp->end_date ? ' – '.$exp->end_date : '') }}
                </div>
                @if($exp->description)
                <div class="r-item-desc">{{ $exp->description }}</div>
                @endif
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
                <div class="r-item-title">{{ $edu->degree }}@if($edu->field_of_study) in {{ $edu->field_of_study }}@endif</div>
                <div class="r-item-sub">{{ $edu->institution }}</div>
                <div class="r-item-date">{{ $edu->start_date }}{{ $edu->end_date ? ' – '.$edu->end_date : '' }}@if($edu->gpa) · GPA: {{ $edu->gpa }}@endif</div>
                @if($edu->description)<div class="r-item-desc">{{ $edu->description }}</div>@endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Projects -->
        @if($resume->projects->isNotEmpty())
        <div class="r-section">
            <div class="r-section-title">Projects</div>
            @foreach($resume->projects as $project)
            <div class="r-item">
                <div class="r-item-title">{{ $project->name }}@if($project->role) <span style="font-weight:400;"> — {{ $project->role }}</span>@endif</div>
                @if($project->technologies)<div class="r-item-sub">{{ $project->technologies }}</div>@endif
                @if($project->description)<div class="r-item-desc">{{ $project->description }}</div>@endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
