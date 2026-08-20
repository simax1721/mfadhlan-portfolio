<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->trans('subtitle'),
            'description' => $this->trans('description'),
            'tech_stack' => $this->tech_stack ?? [],
            'bullets' => $this->trans('bullets') ?? [],
            'image_url' => $this->image_url,
            'demo_url' => $this->demo_url,
            'github_url' => $this->github_url,
            'featured' => (bool) $this->featured,
        ];
    }
}
