<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $resumes = $user->resumes()
            ->with(['educations', 'workExperiences', 'skills', 'projects', 'certifications', 'languages'])
            ->latest()
            ->get();

        $stats = [
            'total'            => $resumes->count(),
            'published'        => $resumes->where('is_draft', false)->count(),
            'drafts'           => $resumes->where('is_draft', true)->count(),
            'avg_strength'     => $resumes->isNotEmpty() ? (int) round($resumes->avg(fn($r) => $r->strength_score)) : 0,
            'total_skills'     => $resumes->sum(fn($r) => $r->skills->count()),
            'total_experience' => $resumes->sum(fn($r) => $r->workExperiences->count()),
        ];

        return view('dashboard.index', compact('resumes', 'stats'));
    }
}
