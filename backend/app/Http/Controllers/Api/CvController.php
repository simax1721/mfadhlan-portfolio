<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EducationEntryResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\OrganizationEntryResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SkillResource;
use App\Models\EducationEntry;
use App\Models\Experience;
use App\Models\OrganizationEntry;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
    /**
     * Renders the CV as a PDF straight from the same CMS data the site
     * uses, so it's always current — no more re-uploading a stale file.
     */
    public function show()
    {
        $locale = app()->getLocale();
        $profile = Profile::current();

        // Resolved as a local filesystem path (not a URL) so dompdf can
        // embed it directly without needing to fetch itself over HTTP.
        $photoPath = $profile->photo && Storage::disk('public')->exists($profile->photo)
            ? Storage::disk('public')->path($profile->photo)
            : null;

        $pdfBinary = Cache::remember("portfolio.cv.{$locale}", now()->addMinutes(30), function () use ($profile, $photoPath, $locale) {
            $skillsByCategory = Skill::orderBy('order')->get()->groupBy('category');

            $data = [
                'locale' => $locale,
                'profile' => (new ProfileResource($profile))->resolve(),
                'photoPath' => $photoPath,
                'skillsByCategory' => $skillsByCategory,
                'experiences' => ExperienceResource::collection(Experience::orderBy('order')->get())->resolve(),
                'projects' => ProjectResource::collection(Project::orderBy('order')->get())->resolve(),
                'education' => EducationEntryResource::collection(EducationEntry::orderBy('order')->get())->resolve(),
                'organizations' => OrganizationEntryResource::collection(OrganizationEntry::orderBy('order')->get())->resolve(),
            ];

            return Pdf::loadView('cv.pdf', $data)
                ->setPaper('a4')
                ->output();
        });

        $slug = str($profile->name ?: 'CV')->slug('-');
        $filename = "CV-{$slug}-" . strtoupper($locale) . '.pdf';

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
