<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesPortfolioCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillCategory extends Model
{
    use InvalidatesPortfolioCache;

    protected $fillable = ['name', 'order', 'highlighted'];

    protected $casts = [
        'highlighted' => 'boolean',
    ];

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('order');
    }
}
