<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EducationEntryResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\OrganizationEntryResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Models\EducationEntry;
use App\Models\Experience;
use App\Models\OrganizationEntry;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SkillCategory;
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

        // Embedded as a base64 data URI rather than passing the Supabase
        // Storage URL straight through, so dompdf never needs its own
        // remote-fetch capability enabled just to render one photo.
        $photoDataUri = null;
        if ($profile->photo && Storage::disk('s3')->exists($profile->photo)) {
            $contents = Storage::disk('s3')->get($profile->photo);
            if ($contents) {
                $mimeType = Storage::disk('s3')->mimeType($profile->photo) ?: 'image/jpeg';
                $photoDataUri = 'data:'.$mimeType.';base64,'.base64_encode($contents);
            }
        }

        $pdfBinary = Cache::remember("portfolio.cv.{$locale}", now()->addMinutes(30), function () use ($profile, $photoDataUri, $locale) {
            $data = [
                'locale' => $locale,
                'profile' => (new ProfileResource($profile))->resolve(),
                'photoDataUri' => $photoDataUri,
                'skillCategories' => SkillCategory::with('skills')->orderBy('order')->get(),
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
        $filename = "CV-{$slug}-".strtoupper($locale).'.pdf';

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
