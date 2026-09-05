<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'group', 'label', 'value_fr', 'value_en', 'value', 'type'];

    public static function map(): array
    {
        return static::query()->get()->mapWithKeys(fn ($item) => [$item->key => $item])->all();
    }

    public function displayValue(): ?string
    {
        return in_array($this->type, ['localized_text', 'localized_textarea'], true)
            ? ($this->{'value_'.app()->getLocale()} ?: $this->value_fr)
            : $this->value;
    }
}
