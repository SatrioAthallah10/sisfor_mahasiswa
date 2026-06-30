<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'id'])) {
            // store in session and cookie so middleware and subsequent requests pick it up
            if (session()->isStarted() || request()->hasSession()) {
                request()->session()->put('locale', $locale);
            }
            cookie()->queue(cookie()->forever('locale', $locale));
            App::setLocale($locale);
            return redirect()->back();
        }

        return redirect()->back();
    }
}
