<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'highlighted' => (bool) $this->highlighted,
            'skills' => SkillResource::collection($this->whenLoaded('skills'))->resolve(),
        ];
    }
}
