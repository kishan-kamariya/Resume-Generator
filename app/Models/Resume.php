<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'template', 'full_name', 'email', 'phone',
        'address', 'city', 'country', 'linkedin', 'github', 'website',
        'job_title', 'summary', 'profile_photo', 'is_draft',
    ];

    protected $casts = [
        'is_draft' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class)->orderBy('order');
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class)->orderBy('order');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('order');
    }

    public function projects()
    {
        return $this->hasMany(Project::class)->orderBy('order');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class)->orderBy('order');
    }

    public function languages()
    {
        return $this->hasMany(Language::class)->orderBy('order');
    }

    /**
     * Calculate ATS / completeness score from 0 to 100%
     */
    public function getStrengthScoreAttribute(): int
    {
        $score = 0;

        // Basic contact info (up to 20 pts)
        if (!empty($this->full_name)) $score += 5;
        if (!empty($this->email)) $score += 5;
        if (!empty($this->phone)) $score += 5;
        if (!empty($this->city) || !empty($this->country)) $score += 5;

        // Title and Summary (up to 20 pts)
        if (!empty($this->job_title)) $score += 5;
        if (!empty($this->summary)) {
            $score += (strlen(trim($this->summary)) >= 40) ? 15 : 8;
        }

        // Work Experience (up to 25 pts)
        $expCount = $this->workExperiences->count();
        if ($expCount >= 2) {
            $score += 25;
        } elseif ($expCount === 1) {
            $score += 15;
        }

        // Education (up to 15 pts)
        if ($this->educations->count() >= 1) {
            $score += 15;
        }

        // Skills (up to 10 pts)
        $skillsCount = $this->skills->count();
        if ($skillsCount >= 4) {
            $score += 10;
        } elseif ($skillsCount >= 1) {
            $score += 5;
        }

        // Projects & Extras (up to 10 pts)
        if ($this->projects->count() >= 1) {
            $score += 5;
        }
        if ($this->certifications->count() >= 1 || $this->languages->count() >= 1) {
            $score += 5;
        }

        return min(100, $score);
    }

    /**
     * Breakdown of checklist items for tooltip / optimization panel
     */
    public function getStrengthBreakdownAttribute(): array
    {
        return [
            ['label' => 'Contact Info & Phone', 'completed' => !empty($this->phone) && (!empty($this->city) || !empty($this->country))],
            ['label' => 'Target Job Title', 'completed' => !empty($this->job_title)],
            ['label' => 'Professional Summary (40+ chars)', 'completed' => !empty($this->summary) && strlen(trim($this->summary)) >= 40],
            ['label' => 'Work Experience (2+ roles)', 'completed' => $this->workExperiences->count() >= 2],
            ['label' => 'Education Listed', 'completed' => $this->educations->count() >= 1],
            ['label' => 'Skills (4+ added)', 'completed' => $this->skills->count() >= 4],
            ['label' => 'Projects & Certifications', 'completed' => ($this->projects->count() + $this->certifications->count()) >= 1],
        ];
    }

    /**
     * Color status based on strength score
     */
    public function getStrengthBadgeColorAttribute(): string
    {
        $score = $this->strength_score;
        if ($score >= 80) return 'success';
        if ($score >= 50) return 'warning';
        return 'danger';
    }
}
