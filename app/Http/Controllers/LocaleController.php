<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'id'])) {
            session()->put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}
