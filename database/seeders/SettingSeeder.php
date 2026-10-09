<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\Settings;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Company -----------------------------------------------------
            ['company.name', 'Global Consultancy Education', 'string', 'company', 'Company name'],
            ['company.legal_name', 'GC CONSULTANCY LIMITED', 'string', 'company', 'Registered name'],
            ['company.company_number', '02773896', 'string', 'company', 'Company number'],
            ['company.tagline', 'Your Dream to Study Abroad - Just Click & Achieve It', 'string', 'company', 'Tagline'],
            ['company.subtagline', 'Find The Right Path with Global Consultancy!', 'string', 'company', 'Secondary tagline'],
            ['company.email', 'info@gconsultancy.co.uk', 'string', 'company', 'Email'],
            ['company.website', 'https://www.gconsultancy.co.uk', 'string', 'company', 'Website'],
            ['company.phone_uk', '+44 7402 993321', 'string', 'company', 'UK phone'],
            ['company.phone_bd_1', '+880 1744 742686', 'string', 'company', 'Bangladesh phone 1'],
            ['company.phone_bd_2', '+880 1935 017746', 'string', 'company', 'Bangladesh phone 2'],
            ['company.address_london', 'Suite 3, 2nd Floor, LMC Business Wing, 38-44 Whitechapel Road, London', 'string', 'company', 'London office'],
            ['company.address_khulna', 'House 52, Alingon House, Road 5, Nirala R/A, Khulna', 'string', 'company', 'Khulna office'],
            ['company.registered_office', '283 Green Lanes, London N13 4XS', 'string', 'company', 'Registered office'],

            // Social ------------------------------------------------------
            ['social.facebook', 'https://www.facebook.com/GCEduLimited', 'string', 'social', 'Facebook'],
            ['social.instagram', 'https://www.instagram.com/global_consultancy_education', 'string', 'social', 'Instagram'],
            ['social.youtube', 'https://www.youtube.com/@globalconsultancy-xf5jh', 'string', 'social', 'YouTube'],
            ['social.twitter', 'https://x.com/GlobalCons78186', 'string', 'social', 'X / Twitter'],
            ['social.whatsapp', 'https://wa.me/447402993321', 'string', 'social', 'WhatsApp'],
            ['social.followers', '19000', 'int', 'social', 'Facebook followers'],

            // Marketing claims used on the public site ---------------------
            ['stats.students_placed', '1000', 'int', 'stats', 'Students placed'],
            ['stats.universities', '50', 'int', 'stats', 'Partner universities'],
            ['stats.countries', '15', 'int', 'stats', 'Destination countries'],

            // CRM ---------------------------------------------------------
            ['crm.timezone', 'Europe/London', 'string', 'crm', 'Timezone'],
            ['crm.per_page', '20', 'int', 'crm', 'Rows per page'],
            ['crm.max_upload_mb', '10', 'int', 'crm', 'Maximum upload size (MB)'],

            // Finance -----------------------------------------------------
            ['finance.default_currency', 'GBP', 'string', 'finance', 'Default currency'],
            ['finance.currencies', '["GBP","USD","CAD","AUD","EUR","BDT"]', 'json', 'finance', 'Supported currencies'],
            ['finance.clawback_months', '6', 'int', 'finance', 'Clawback window (months)'],

            // GDPR --------------------------------------------------------
            ['gdpr.retention_months', '72', 'int', 'gdpr', 'Data retention (months)'],
            ['gdpr.policy_version', '1.0', 'string', 'gdpr', 'Privacy policy version'],
            ['gdpr.consent_required', '1', 'bool', 'gdpr', 'Consent required at registration'],

            // SLA ---------------------------------------------------------
            ['sla.new_lead_hours', '2', 'int', 'sla', 'Contact a new lead within (hours)'],
            ['sla.new_application_days', '1', 'int', 'sla', 'Review a new application within (days)'],
            ['sla.missing_document_days', '2', 'int', 'sla', 'Chase a missing document within (days)'],
        ];

        foreach ($settings as [$key, $value, $type, $group, $label]) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group, 'label' => $label],
            );
        }

        app(Settings::class)->flush();
    }
}
