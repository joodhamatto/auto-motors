<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasLocalizedContent;

    protected $fillable = ['product_category_id', 'name_fr', 'name_en', 'description_fr', 'description_en', 'price', 'image_reference', 'main_image', 'is_active', 'display_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'price' => 'decimal:2'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }
}
