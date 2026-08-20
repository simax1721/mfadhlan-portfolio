<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationEntryResource;
use App\Models\OrganizationEntry;

class OrganizationEntryController extends Controller
{
    public function index()
    {
        return OrganizationEntryResource::collection(OrganizationEntry::orderBy('order')->get());
    }
}
