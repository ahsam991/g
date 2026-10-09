<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Dream to Study Abroad? <span class="italic text-orange">Just Click &amp; Achieve It</span>',
                'subtitle' => 'Find The Right Path with Global Consultancy! 100% FREE counselling and application processing.',
                'badge_text' => 'Admission Open for January Intake - 2026',
                'cta_text' => 'Book Free Counselling',
                'cta_link' => '/contact',
                'cta2_text' => 'Check Eligibility',
                'cta2_link' => '/courses',
                'sort_order' => 0,
            ],
            [
                'title' => 'Study in the <span class="italic text-orange">United Kingdom</span>',
                'subtitle' => 'Russell Group universities, 2-year post-study work visa. Start your journey to the UK today.',
                'badge_text' => '1000+ Students Placed Successfully',
                'cta_text' => 'Explore UK Universities',
                'cta_link' => '/destinations',
                'cta2_text' => 'Free Consultation',
                'cta2_link' => '/contact',
                'sort_order' => 1,
            ],
            [
                'title' => 'Your Visa, <span class="italic text-orange">Our Expertise</span>',
                'subtitle' => 'Expert visa guidance with high approval rates. Document checks, SOP guidance, and interview preparation.',
                'badge_text' => 'British Council Certified Counsellors',
                'cta_text' => 'Start Your Application',
                'cta_link' => '/contact',
                'cta2_text' => 'Our Services',
                'cta2_link' => '/services',
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $slide) {
            Slider::create($slide);
        }
    }
}
