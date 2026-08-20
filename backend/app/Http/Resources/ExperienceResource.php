<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'company' => $this->company,
            'period' => $this->period,
            'tech_stack' => $this->tech_stack ?? [],
            'bullets' => $this->trans('bullets') ?? [],
        ];
    }
}
