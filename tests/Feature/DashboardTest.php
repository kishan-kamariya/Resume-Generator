<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\WorkExperience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_advanced_dashboard(): void
    {
        $user = User::factory()->create();

        $resume = Resume::create([
            'user_id'    => $user->id,
            'title'      => 'Senior Laravel Developer',
            'template'   => 'modern',
            'full_name'  => 'Alex Morgan',
            'email'      => 'alex@gmail.com',
            'phone'      => '+1 555-0199',
            'job_title'  => 'Senior Full Stack Engineer',
            'summary'    => 'Over 8 years of experience engineering high-scale web platforms.',
            'is_draft'   => false,
        ]);

        Skill::create([
            'resume_id' => $resume->id,
            'name'      => 'PHP & Laravel',
            'level'     => 'Expert',
            'order'     => 0,
        ]);

        WorkExperience::create([
            'resume_id' => $resume->id,
            'company'   => 'TechCorp',
            'position'  => 'Senior Engineer',
            'order'     => 0,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Resume Hub');
        $response->assertSee('Senior Laravel Developer');
        $response->assertSee('Quick-Start From a Curated Template');
        $response->assertSee('ATS Readiness Checklist');
        $response->assertSee('AI Career Shortcuts');
    }

    public function test_user_can_duplicate_their_resume(): void
    {
        $user = User::factory()->create();

        $resume = Resume::create([
            'user_id'    => $user->id,
            'title'      => 'Base Resume',
            'template'   => 'classic',
            'full_name'  => 'Alex Morgan',
            'email'      => 'alex@gmail.com',
            'is_draft'   => false,
        ]);

        Skill::create([
            'resume_id' => $resume->id,
            'name'      => 'Vue.js',
            'level'     => 'Advanced',
            'order'     => 0,
        ]);

        $response = $this->actingAs($user)->post(route('resume.duplicate', $resume->id));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('resumes', [
            'user_id'  => $user->id,
            'title'    => 'Base Resume (Copy)',
            'is_draft' => true,
        ]);

        $copy = Resume::where('title', 'Base Resume (Copy)')->first();
        $this->assertNotNull($copy);
        $this->assertEquals(1, $copy->skills()->count());
        $this->assertEquals('Vue.js', $copy->skills()->first()->name);
    }

    public function test_user_cannot_duplicate_another_users_resume(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $resume = Resume::create([
            'user_id'    => $user1->id,
            'title'      => 'Secret Resume',
            'template'   => 'classic',
            'full_name'  => 'User One',
            'email'      => 'user1@gmail.com',
            'is_draft'   => false,
        ]);

        $response = $this->actingAs($user2)->post(route('resume.duplicate', $resume->id));
        $response->assertStatus(403);
    }
}
