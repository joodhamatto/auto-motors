<?php

namespace App\Models\Concerns;

trait HasLocalizedContent
{
    public function localized(string $field): ?string
    {
        $locale = app()->getLocale();

        return $this->{$field.'_'.$locale} ?: $this->{$field.'_fr'};
    }
}
