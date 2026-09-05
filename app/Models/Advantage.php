<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class Advantage extends Model
{
    use HasLocalizedContent;

    protected $fillable = ['title_fr', 'title_en', 'icon', 'is_active', 'display_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
