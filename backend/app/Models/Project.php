<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use App\Models\Concerns\InvalidatesPortfolioCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasLocalizedFields, InvalidatesPortfolioCache;

    protected $fillable = [
        'title', 'subtitle_id', 'subtitle_en', 'description_id', 'description_en',
        'tech_stack', 'bullets_id', 'bullets_en',
        'image', 'demo_url', 'github_url', 'featured', 'order',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'bullets_id' => 'array',
        'bullets_en' => 'array',
        'featured' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }
}
