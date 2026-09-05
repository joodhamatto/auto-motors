<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasLocalizedContent;

    protected $fillable = ['question_fr', 'question_en', 'answer_fr', 'answer_en', 'is_active', 'display_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
