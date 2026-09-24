<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Education;
use App\Models\WorkExperience;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Certification;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    public function create()
    {
        return view('resume.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email'     => ['required', 'email', 'ends_with:@gmail.com'],
        ], [
            'email.required'  => 'Email address is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.ends_with' => 'Email address must be a valid @gmail.com address.',
        ]);

        $resumeData = $request->only([
            'title', 'template', 'full_name', 'email', 'phone',
            'address', 'city', 'country', 'linkedin', 'github', 'website',
            'job_title', 'summary',
        ]);
        $resumeData['user_id'] = Auth::id();
        $resumeData['is_draft'] = $request->has('save_draft');
        $resumeData['template'] = $request->input('template', 'classic');

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('photos', 'public');
            $resumeData['profile_photo'] = $path;
        }

        $resume = Resume::create($resumeData);

        // Education
        if ($request->has('education')) {
            foreach ($request->education as $i => $edu) {
                if (!empty($edu['institution'])) {
                    Education::create(array_merge($edu, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        // Work Experience
        if ($request->has('work_experience')) {
            foreach ($request->work_experience as $i => $exp) {
                if (!empty($exp['company'])) {
                    WorkExperience::create(array_merge($exp, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        // Skills
        if ($request->has('skills')) {
            foreach ($request->skills as $i => $skill) {
                if (!empty($skill['name'])) {
                    Skill::create(array_merge($skill, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        // Projects
        if ($request->has('projects')) {
            foreach ($request->projects as $i => $project) {
                if (!empty($project['name'])) {
                    Project::create(array_merge($project, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        // Certifications
        if ($request->has('certifications')) {
            foreach ($request->certifications as $i => $cert) {
                if (!empty($cert['name'])) {
                    Certification::create(array_merge($cert, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        // Languages
        if ($request->has('languages')) {
            foreach ($request->languages as $i => $lang) {
                if (!empty($lang['name'])) {
                    Language::create(array_merge($lang, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        return redirect()->route('resume.preview', $resume->id)
            ->with('success', 'Resume created successfully!');
    }

    public function edit(Resume $resume)
    {
        $this->authorize_resume($resume);
        $resume->load(['educations', 'workExperiences', 'skills', 'projects', 'certifications', 'languages']);
        return view('resume.edit', compact('resume'));
    }

    public function update(Request $request, Resume $resume)
    {
        $this->authorize_resume($resume);

        $request->validate([
            'title'     => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email'     => ['required', 'email', 'ends_with:@gmail.com'],
        ], [
            'email.required'  => 'Email address is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.ends_with' => 'Email address must be a valid @gmail.com address.',
        ]);

        $resumeData = $request->only([
            'title', 'template', 'full_name', 'email', 'phone',
            'address', 'city', 'country', 'linkedin', 'github', 'website',
            'job_title', 'summary',
        ]);
        $resumeData['is_draft'] = $request->has('save_draft');

        if ($request->hasFile('profile_photo')) {
            if ($resume->profile_photo) {
                Storage::disk('public')->delete($resume->profile_photo);
            }
            $path = $request->file('profile_photo')->store('photos', 'public');
            $resumeData['profile_photo'] = $path;
        }

        $resume->update($resumeData);

        // Sync related data
        $resume->educations()->delete();
        if ($request->has('education')) {
            foreach ($request->education as $i => $edu) {
                if (!empty($edu['institution'])) {
                    Education::create(array_merge($edu, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        $resume->workExperiences()->delete();
        if ($request->has('work_experience')) {
            foreach ($request->work_experience as $i => $exp) {
                if (!empty($exp['company'])) {
                    WorkExperience::create(array_merge($exp, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        $resume->skills()->delete();
        if ($request->has('skills')) {
            foreach ($request->skills as $i => $skill) {
                if (!empty($skill['name'])) {
                    Skill::create(array_merge($skill, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        $resume->projects()->delete();
        if ($request->has('projects')) {
            foreach ($request->projects as $i => $project) {
                if (!empty($project['name'])) {
                    Project::create(array_merge($project, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        $resume->certifications()->delete();
        if ($request->has('certifications')) {
            foreach ($request->certifications as $i => $cert) {
                if (!empty($cert['name'])) {
                    Certification::create(array_merge($cert, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        $resume->languages()->delete();
        if ($request->has('languages')) {
            foreach ($request->languages as $i => $lang) {
                if (!empty($lang['name'])) {
                    Language::create(array_merge($lang, ['resume_id' => $resume->id, 'order' => $i]));
                }
            }
        }

        return redirect()->route('resume.preview', $resume->id)
            ->with('success', 'Resume updated successfully!');
    }

    public function preview(Resume $resume, Request $request)
    {
        $this->authorize_resume($resume);
        $resume->load(['educations', 'workExperiences', 'skills', 'projects', 'certifications', 'languages']);

        // Allow template override via query param
        if ($request->query('template') && in_array($request->query('template'), ['classic', 'modern', 'minimal'])) {
            $resume->template = $request->query('template');
        }

        return view('resume.preview', compact('resume'));
    }

    public function download(Resume $resume)
    {
        $this->authorize_resume($resume);
        $resume->load(['educations', 'workExperiences', 'skills', 'projects', 'certifications', 'languages']);

        $template = 'resume.templates.' . $resume->template;
        $pdf = Pdf::loadView($template, compact('resume'))
            ->setPaper('a4', 'portrait');

        $filename = str_replace(' ', '_', strtolower($resume->full_name ?? 'resume')) . '_resume.pdf';
        return $pdf->download($filename);
    }

    public function destroy(Resume $resume)
    {
        $this->authorize_resume($resume);
        if ($resume->profile_photo) {
            Storage::disk('public')->delete($resume->profile_photo);
        }
        $resume->delete();
        return redirect()->route('dashboard')->with('success', 'Resume deleted successfully.');
    }

    public function duplicate(Resume $resume)
    {
        $this->authorize_resume($resume);
        $resume->load(['educations', 'workExperiences', 'skills', 'projects', 'certifications', 'languages']);

        $cloned = $resume->replicate();
        $cloned->title = $resume->title . ' (Copy)';
        $cloned->is_draft = true;
        $cloned->save();

        foreach ($resume->educations as $edu) {
            $cloned->educations()->create($edu->replicate()->toArray());
        }
        foreach ($resume->workExperiences as $exp) {
            $cloned->workExperiences()->create($exp->replicate()->toArray());
        }
        foreach ($resume->skills as $skill) {
            $cloned->skills()->create($skill->replicate()->toArray());
        }
        foreach ($resume->projects as $proj) {
            $cloned->projects()->create($proj->replicate()->toArray());
        }
        foreach ($resume->certifications as $cert) {
            $cloned->certifications()->create($cert->replicate()->toArray());
        }
        foreach ($resume->languages as $lang) {
            $cloned->languages()->create($lang->replicate()->toArray());
        }

        return redirect()->route('dashboard')
            ->with('success', 'Resume "' . $cloned->title . '" duplicated successfully!');
    }

    private function authorize_resume(Resume $resume)
    {
        if ($resume->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
