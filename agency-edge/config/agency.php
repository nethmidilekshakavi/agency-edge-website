<?php

return [
    // Address that receives an email for every new enquiry. Empty = admin panel only.
    'leads_email' => env('AGENCY_LEADS_EMAIL'),

    // First admin account, created by DatabaseSeeder.
    'admin' => [
        'name' => env('ADMIN_NAME', 'Agency Edge Admin'),
        'email' => env('ADMIN_EMAIL', 'admin@example.com'),
        'password' => env('ADMIN_PASSWORD', 'change-me-now'),
    ],
];
