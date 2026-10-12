<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\University;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedDestinations();
        $this->seedUniversities();
        $this->seedServices();
        $this->seedTestimonials();
        $this->seedPartners();
    }

    private function seedDestinations(): void
    {
        $destinations = [
            ['name' => 'United Kingdom', 'flag' => "\u{1F1EC}\u{1F1E7}", 'short_description' => 'Russell Group universities, 2-year post-study work visa', 'gradient_classes' => 'from-blue-50 to-red-50', 'featured' => true],
            ['name' => 'USA', 'flag' => "\u{1F1FA}\u{1F1F8}", 'short_description' => 'Ivy League and state universities, OPT work options', 'gradient_classes' => 'from-blue-50 to-indigo-50', 'featured' => true],
            ['name' => 'Canada', 'flag' => "\u{1F1E8}\u{1F1E6}", 'short_description' => 'Affordable tuition, work while you study, path to residency', 'gradient_classes' => 'from-red-50 to-orange-50', 'featured' => true],
            ['name' => 'Australia', 'flag' => "\u{1F1E6}\u{1F1FA}", 'short_description' => 'Globally recognised degrees, generous post-study work rights', 'gradient_classes' => 'from-yellow-50 to-green-50', 'featured' => true],
            ['name' => 'Malaysia', 'flag' => "\u{1F1F2}\u{1F1FE}", 'short_description' => 'Affordable global campuses, gateway to Asia', 'gradient_classes' => 'from-blue-50 to-yellow-50', 'featured' => true],
            ['name' => 'Europe', 'flag' => "\u{1F1EA}\u{1F1FA}", 'short_description' => 'Low or no tuition, English-taught programmes', 'gradient_classes' => 'from-indigo-50 to-blue-50', 'featured' => true],
        ];

        foreach ($destinations as $i => $d) {
            Destination::create(array_merge($d, ['sort_order' => $i]));
        }
    }

    private function seedUniversities(): void
    {
        $uk = Destination::where('name', 'United Kingdom')->value('id');
        $usa = Destination::where('name', 'USA')->value('id');
        $canada = Destination::where('name', 'Canada')->value('id');
        $australia = Destination::where('name', 'Australia')->value('id');

        $unis = [
            ['name' => 'University of Greenwich', 'destination_id' => $uk, 'city' => 'London', 'partner' => true, 'featured' => true],
            ['name' => 'University of East London', 'destination_id' => $uk, 'city' => 'London', 'partner' => true, 'featured' => true],
            ['name' => 'London Metropolitan University', 'destination_id' => $uk, 'city' => 'London', 'partner' => true],
            ['name' => 'University of Bedfordshire', 'destination_id' => $uk, 'city' => 'Luton', 'partner' => true],
            ['name' => 'University of Sunderland', 'destination_id' => $uk, 'city' => 'Sunderland', 'partner' => true],
            ['name' => 'Coventry University', 'destination_id' => $uk, 'city' => 'Coventry', 'partner' => true, 'featured' => true],
            ['name' => 'University of West of England', 'destination_id' => $uk, 'city' => 'Bristol', 'partner' => true],
            ['name' => 'Middlesex University', 'destination_id' => $uk, 'city' => 'London', 'partner' => true],
            ['name' => 'Birmingham City University', 'destination_id' => $uk, 'city' => 'Birmingham', 'partner' => true],
            ['name' => 'University of Hertfordshire', 'destination_id' => $uk, 'city' => 'Hatfield', 'partner' => true],
            ['name' => 'University of Toronto', 'destination_id' => $canada, 'city' => 'Toronto', 'partner' => true, 'featured' => true],
            ['name' => 'University of British Columbia', 'destination_id' => $canada, 'city' => 'Vancouver', 'partner' => true],
            ['name' => 'University of Melbourne', 'destination_id' => $australia, 'city' => 'Melbourne', 'partner' => true, 'featured' => true],
            ['name' => 'University of Sydney', 'destination_id' => $australia, 'city' => 'Sydney', 'partner' => true],
            ['name' => 'New York University', 'destination_id' => $usa, 'city' => 'New York', 'partner' => true],
            ['name' => 'Boston University', 'destination_id' => $usa, 'city' => 'Boston', 'partner' => true],
        ];

        foreach ($unis as $i => $u) {
            University::create(array_merge($u, ['sort_order' => $i]));
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['title' => 'Free Counselling', 'short_description' => 'Expert guidance at no cost on choosing a university and program. Honest, no-pressure advice from certified counsellors.', 'icon_svg' => 'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155', 'featured' => true],
            ['title' => 'Admission Support', 'short_description' => 'University application help, course shortlisting, document preparation and follow-up with institutions.', 'icon_svg' => 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z', 'featured' => true],
            ['title' => 'Visa Compliance', 'short_description' => 'Document checks, SOP guidance, interview preparation. Pre-visa interview prep for high approval rates.', 'icon_svg' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z', 'featured' => true],
            ['title' => 'Student Accommodation', 'short_description' => 'Verified housing arranged before you fly, close to your campus.', 'icon_svg' => 'M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819', 'featured' => true],
            ['title' => 'IELTS & English Prep', 'short_description' => 'Training and preparation to meet language requirements. IELTS coaching available.', 'icon_svg' => 'M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 0 1 6-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364V3m0 2.364a48.174 48.174 0 0 1 0 0', 'featured' => true],
            ['title' => 'End-to-End Support', 'short_description' => 'From first chat to post-arrival settling in. BRP collection, bank accounts and pre-departure briefing.', 'icon_svg' => 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z', 'featured' => true],
            ['title' => 'Scholarship Guidance', 'short_description' => 'Find and apply for scholarships that match your profile, saving thousands on tuition.', 'icon_svg' => 'M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
            ['title' => 'Career Planning', 'short_description' => 'CV writing, interview skills, and job placement advice for after graduation.', 'icon_svg' => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0'],
        ];

        foreach ($services as $i => $s) {
            Service::create(array_merge($s, ['sort_order' => $i]));
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['name' => 'Pushpita Das', 'country_flag' => "\u{1F1EC}\u{1F1E7}", 'visa_type' => 'UK Student Visa', 'quote' => 'The entire visa process was smooth. Global Consultancy guided me step by step with clear instructions.'],
            ['name' => 'Jannatul Ferdous', 'country_flag' => "\u{1F1EC}\u{1F1E7}", 'visa_type' => 'UK Student Visa', 'quote' => 'From choosing the right university to getting my visa approved, they were with me at every step.'],
            ['name' => 'Fahima Momtaz', 'country_flag' => "\u{1F1EC}\u{1F1E7}", 'visa_type' => 'UK Student Visa', 'quote' => 'I got my visa approved on the first attempt! Their interview preparation made all the difference.'],
            ['name' => 'Abdur Rahman Fahad', 'country_flag' => "\u{1F1E8}\u{1F1E6}", 'visa_type' => 'Canada Study Permit', 'quote' => 'They helped me find the right programme and handled all my documentation. Highly recommend!'],
            ['name' => 'Maisha Ibnat', 'country_flag' => "\u{1F1E6}\u{1F1FA}", 'visa_type' => 'Australia Student Visa', 'quote' => 'Offer, visa and accommodation sorted in one smooth journey. Could not have done it without them.'],
            ['name' => 'Sanjida Sultana Mahin', 'country_flag' => "\u{1F1F2}\u{1F1FE}", 'visa_type' => 'Malaysia Student Visa', 'quote' => 'Affordable and professional service. They found me the perfect university in Malaysia.'],
        ];

        foreach ($testimonials as $i => $t) {
            Testimonial::create(array_merge($t, ['sort_order' => $i]));
        }
    }

    private function seedPartners(): void
    {
        $partners = [
            ['name' => 'British Council'],
            ['name' => 'UCAS'],
            ['name' => 'IDP Education'],
            ['name' => 'IELTS'],
            ['name' => 'Universities UK'],
        ];

        foreach ($partners as $i => $p) {
            Partner::create(array_merge($p, ['sort_order' => $i]));
        }
    }
}
