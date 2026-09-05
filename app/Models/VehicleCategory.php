<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleCategory extends Model
{
    use HasLocalizedContent;

    protected $fillable = ['name_fr', 'name_en', 'slug', 'is_active', 'display_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
