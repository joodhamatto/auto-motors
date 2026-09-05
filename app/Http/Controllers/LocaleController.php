<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    public function __invoke(string $locale)
    {
        abort_unless(in_array($locale, ['fr', 'en'], true), 404);
        session(['locale' => $locale]);

        return back();
    }
}
