<?php

namespace App\Support;

/**
 * The single source of truth for permission slugs. Grouped for the admin
 * permission grid. Admin holds all of these implicitly; the consultant list
 * below is what gets attached to that role by the seeder.
 */
final class Permissions
{
    /** @var array<string, array<string, string>> group => [slug => label] */
    public const CATALOGUE = [
        'Students' => [
            'candidates.view' => 'View students',
            'candidates.create' => 'Create students',
            'candidates.update' => 'Edit students',
            'candidates.delete' => 'Archive students',
            'candidates.assign' => 'Assign students to a consultant',
            'candidates.import' => 'Import and export students',
        ],
        'Leads' => [
            'leads.view' => 'View leads',
            'leads.manage' => 'Create and edit leads',
            'leads.convert' => 'Convert a lead into a student',
        ],
        'Documents' => [
            'documents.view' => 'View documents',
            'documents.upload' => 'Upload documents',
            'documents.verify' => 'Verify or reject documents',
            'documents.reverse' => 'Reverse a document rejection',
            'documents.request' => 'Request documents from a student',
        ],
        'Catalogue' => [
            'catalogue.view' => 'View universities and courses',
            'catalogue.manage' => 'Manage universities and courses',
        ],
        'Applications' => [
            'applications.view' => 'View applications',
            'applications.create' => 'Create applications',
            'applications.update' => 'Edit applications',
            'applications.status.change' => 'Move an application forward',
            'applications.status.close' => 'Close or reject an application',
        ],
        'Journey' => [
            'journey.manage' => 'Manage offers, deposits, CAS, visa and enrolment',
        ],
        'Operations' => [
            'tasks.manage' => 'Manage tasks and notes',
            'appointments.manage' => 'Manage appointments and availability',
            'messages.send' => 'Message students',
        ],
        'Finance' => [
            'finance.view.own' => 'View own commission lines',
            'finance.view.all' => 'View all finance records',
            'finance.manage' => 'Manage commissions, invoices and payments',
        ],
        'Reports' => [
            'reports.view' => 'View reports',
            'reports.export' => 'Export reports',
        ],
        'Administration' => [
            'settings.manage' => 'Manage settings',
            'users.manage' => 'Manage users and roles',
            'audit.view' => 'View audit logs',
            'gdpr.manage' => 'Run GDPR exports, anonymisation and deletions',
            'backup.manage' => 'Run and download backups',
        ],
    ];

    /** Permissions attached to the consultant role. */
    public const CONSULTANT = [
        'candidates.view', 'candidates.create', 'candidates.update', 'candidates.import',
        'leads.view', 'leads.manage', 'leads.convert',
        'documents.view', 'documents.upload', 'documents.verify', 'documents.request',
        'catalogue.view',
        'applications.view', 'applications.create', 'applications.update', 'applications.status.change',
        'journey.manage',
        'tasks.manage', 'appointments.manage', 'messages.send',
        'finance.view.own',
        'reports.view',
    ];

    /** Permissions attached to the student role. Portal access is policy-driven. */
    public const STUDENT = [
        'documents.upload',
        'catalogue.view',
    ];

    /** @return array<int, string> */
    public static function all(): array
    {
        $slugs = [];

        foreach (self::CATALOGUE as $permissions) {
            $slugs = [...$slugs, ...array_keys($permissions)];
        }

        return $slugs;
    }

    public static function groupOf(string $slug): string
    {
        foreach (self::CATALOGUE as $group => $permissions) {
            if (array_key_exists($slug, $permissions)) {
                return $group;
            }
        }

        return 'Other';
    }

    public static function labelOf(string $slug): string
    {
        foreach (self::CATALOGUE as $permissions) {
            if (array_key_exists($slug, $permissions)) {
                return $permissions[$slug];
            }
        }

        return $slug;
    }
}
