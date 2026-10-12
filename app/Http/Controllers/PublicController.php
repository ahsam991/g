<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\University;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $sliders = Slider::active()->get();
        $destinations = Destination::featured()->get();
        $services = Service::featured()->get();
        $testimonials = Testimonial::active()->get();
        $partnerCount = University::partner()->count();
        $destinationCount = Destination::where('active', true)->count();

        return view('public.home', compact(
            'sliders', 'destinations', 'services', 'testimonials',
            'partnerCount', 'destinationCount'
        ));
    }

    public function about(): View
    {
        $testimonials = Testimonial::active()->take(3)->get();

        return view('public.about', compact('testimonials'));
    }

    public function destinations(): View
    {
        $destinations = Destination::active()
            ->withCount(['universities' => fn ($q) => $q->where('active', true)])
            ->get();

        return view('public.destinations', compact('destinations'));
    }

    public function courses(): View
    {
        $destinations = Destination::active()->with([
            'universities' => fn ($q) => $q->active()->with(['courses' => fn ($c) => $c->active()]),
        ])->get();

        return view('public.courses', compact('destinations'));
    }

    public function services(): View
    {
        $services = Service::active()->get();

        return view('public.services', compact('services'));
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
