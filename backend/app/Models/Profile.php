<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use App\Models\Concerns\InvalidatesPortfolioCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasLocalizedFields, InvalidatesPortfolioCache;

    protected $fillable = [
        'name', 'role', 'tagline_id', 'tagline_en', 'summary_id', 'summary_en',
        'email', 'phone', 'location', 'github_url', 'linkedin_url', 'cv_file', 'photo',
    ];

    protected $appends = ['cv_url', 'photo_url'];

    public function getCvUrlAttribute(): ?string
    {
        return $this->cv_file ? Storage::disk('public')->url($this->cv_file) : null;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::disk('public')->url($this->photo) : null;
    }

    /** There is only ever one profile row; create it on first access. */
    public static function current(): self
    {
        return static::first() ?? static::create([
            'name' => 'Your Name',
            'role' => 'Backend / Fullstack Developer',
            'summary_en' => '',
            'email' => 'you@example.com',
            'location' => 'Indonesia',
        ]);
    }
}
