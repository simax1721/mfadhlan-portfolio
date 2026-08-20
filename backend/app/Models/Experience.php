<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use App\Models\Concerns\InvalidatesPortfolioCache;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasLocalizedFields, InvalidatesPortfolioCache;

    protected $fillable = [
        'title', 'company', 'period', 'tech_stack', 'bullets_id', 'bullets_en', 'order',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'bullets_id' => 'array',
        'bullets_en' => 'array',
    ];
}
