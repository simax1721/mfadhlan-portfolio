<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesPortfolioCache;
use Illuminate\Database\Eloquent\Model;

class EducationEntry extends Model
{
    use InvalidatesPortfolioCache;

    protected $table = 'education_entries';

    protected $fillable = ['degree', 'institution', 'period', 'order'];
}
