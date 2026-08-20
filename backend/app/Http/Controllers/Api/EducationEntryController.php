<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EducationEntryResource;
use App\Models\EducationEntry;

class EducationEntryController extends Controller
{
    public function index()
    {
        return EducationEntryResource::collection(EducationEntry::orderBy('order')->get());
    }
}
