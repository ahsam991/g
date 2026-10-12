<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Destination;
use App\Models\Lead;
use App\Models\University;
use App\Models\WebsiteEnquiry;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $studentCount = Candidate::visibleTo($user)->count();
        $leadCount = Lead::visibleTo($user)->whereNull('converted_at')->count();
        $enquiryCount = WebsiteEnquiry::where('status', 'new')->count();
        $universityCount = University::where('active', true)->count();
        $destinationCount = Destination::where('active', true)->count();

        $recentLeads = Lead::visibleTo($user)
            ->with('source')
            ->latest()
            ->take(5)
            ->get();

        $recentStudents = Candidate::visibleTo($user)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'studentCount',
            'leadCount',
            'enquiryCount',
            'universityCount',
            'destinationCount',
            'recentLeads',
            'recentStudents',
        ));
    }
}
