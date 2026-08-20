<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillCategoryResource;
use App\Models\SkillCategory;

class SkillController extends Controller
{
    public function index()
    {
        return SkillCategoryResource::collection(
            SkillCategory::with('skills')->orderBy('order')->get()
        );
    }
}
