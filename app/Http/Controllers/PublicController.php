<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/** Serves the public marketing pages. These are open to everyone. */
class PublicController extends Controller
{
    public function home(): View
    {
        return view('public.home');
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function destinations(): View
    {
        return view('public.destinations');
    }

    public function courses(): View
    {
        return view('public.courses');
    }

    public function services(): View
    {
        return view('public.services');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function privacy(): View
    {
        return view('public.legal', [
            'title' => 'Privacy Policy',
            'body' => view('public.legal-privacy')->render(),
        ]);
    }

    public function terms(): View
    {
        return view('public.legal', [
            'title' => 'Terms of Service',
            'body' => view('public.legal-terms')->render(),
        ]);
    }
}
