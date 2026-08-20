<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use App\Models\Concerns\InvalidatesPortfolioCache;
use Illuminate\Database\Eloquent\Model;

class OrganizationEntry extends Model
{
    use HasLocalizedFields, InvalidatesPortfolioCache;

    protected $table = 'organization_entries';

    protected $fillable = ['role', 'organization', 'year', 'description_id', 'description_en', 'order'];
}
