<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesPortfolioCache;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use InvalidatesPortfolioCache;

    protected $fillable = ['name', 'category', 'order'];
}
