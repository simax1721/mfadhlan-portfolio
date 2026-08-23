<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'role' => $this->role,
            'tagline' => $this->trans('tagline'),
            'summary' => $this->trans('summary'),
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'github_url' => $this->github_url,
            'linkedin_url' => $this->linkedin_url,
            'cv_url' => $this->cv_url,
            'photo_url' => $this->photo_url,
        ];
    }
}
