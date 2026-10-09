<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnquiryRequest;
use App\Models\WebsiteEnquiry;
use App\Services\LeadCreator;
use Illuminate\Http\RedirectResponse;

class EnquiryController extends Controller
{
    public function store(EnquiryRequest $request, LeadCreator $leadCreator): RedirectResponse
    {
        $data = $request->safe()->except('website');
        $data['type'] ??= 'contact';
        $data['email'] = strtolower(trim($data['email']));
        $data['source'] = 'Website';
        $data['ip_address'] = $request->ip();

        $enquiry = WebsiteEnquiry::create($data);

        // Auto-create a lead from the enquiry
        $leadCreator->fromEnquiry($enquiry);

        return back()->with('enquiry_sent', true);
    }
}
