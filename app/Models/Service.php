<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasLocalizedContent;

    protected $fillable = ['title_fr', 'title_en', 'description_fr', 'description_en', 'icon', 'image', 'is_active', 'display_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
