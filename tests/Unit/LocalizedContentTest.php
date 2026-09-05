<?php

namespace Tests\Unit;

use App\Models\Service;
use Tests\TestCase;

class LocalizedContentTest extends TestCase
{
    public function test_it_returns_the_current_translation_and_falls_back_to_french(): void
    {
        $service = new Service([
            'title_fr' => 'Pièces automobiles',
            'title_en' => 'Automotive parts',
        ]);

        app()->setLocale('en');
        $this->assertSame('Automotive parts', $service->localized('title'));

        $service->title_en = '';
        $this->assertSame('Pièces automobiles', $service->localized('title'));
    }
}
