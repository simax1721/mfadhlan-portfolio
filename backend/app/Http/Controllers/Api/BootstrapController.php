<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EducationEntryResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\OrganizationEntryResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SkillCategoryResource;
use App\Models\EducationEntry;
use App\Models\Experience;
use App\Models\OrganizationEntry;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SkillCategory;
use Illuminate\Support\Facades\Cache;

class BootstrapController extends Controller
{
    /**
     * Everything the public site needs in a single response, so the
     * frontend fires one request on load instead of six in parallel.
     */
    public function index()
    {
        $locale = app()->getLocale();

        $payload = Cache::remember("portfolio.bootstrap.{$locale}", now()->addMinutes(5), function () {
            return [
                'profile' => (new ProfileResource(Profile::current()))->resolve(),
                'skills' => SkillCategoryResource::collection(
                    SkillCategory::with('skills')->orderBy('order')->get()
                )->resolve(),
                'experiences' => ExperienceResource::collection(Experience::orderBy('order')->get())->resolve(),
                'projects' => ProjectResource::collection(Project::orderBy('order')->get())->resolve(),
                'education' => EducationEntryResource::collection(EducationEntry::orderBy('order')->get())->resolve(),
                'organizations' => OrganizationEntryResource::collection(OrganizationEntry::orderBy('order')->get())->resolve(),
            ];
        });

        return response()->json($payload);
    }
}
