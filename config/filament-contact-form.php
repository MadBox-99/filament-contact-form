<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    |
    | The Eloquent model representing the "owner" of a contact form. Set to
    | `null` to disable tenant scoping entirely (single-tenant installation).
    |
    */
    'tenant_model' => null,

    /*
    |--------------------------------------------------------------------------
    | Tenant Foreign Key
    |--------------------------------------------------------------------------
    |
    | The column on the contact_forms / contact_submissions tables that
    | references the tenant.
    |
    */
    'tenant_foreign_key' => 'team_id',

    'tenant_slug_column' => 'slug',

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | `/contact-forms/{slug}` renders a full-page form, `/embed/contact-forms/{slug}`
    | serves an iframe-friendly version and `/contact-forms/embed.js` serves
    | the widget loader.
    |
    */
    'routes' => [
        'enabled' => true,
        'middleware' => ['web'],
        'show_path' => 'contact-forms/{slug}',
        'embed_path' => 'embed/contact-forms/{slug}',
        'widget_script_path' => 'contact-forms/embed.js',
        'throttle' => [
            'show' => '60,1',
            'embed' => '60,1',
            'script' => '120,1',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Submission rate limiting
    |--------------------------------------------------------------------------
    |
    | Per-IP limit for public submissions (attempts per decay window).
    |
    */
    'submission_rate_limit' => [
        'attempts' => 5,
        'decay_seconds' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Embed security
    |--------------------------------------------------------------------------
    |
    | `frame_ancestors` is the Content-Security-Policy frame-ancestors source
    | list of the iframe page. For production, set an explicit allowlist such
    | as `"https://example.com https://*.example.com"`.
    |
    */
    'embed' => [
        'frame_ancestors' => '*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom CSS
    |--------------------------------------------------------------------------
    */
    'custom_css' => [
        'enabled' => true,
        'max_length' => 20000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Lead link
    |--------------------------------------------------------------------------
    |
    | Optional callable `fn (Model $lead): ?string` returning an admin URL for
    | the lead record a host app attached to a submission. Configure it from a
    | service provider: `config(['filament-contact-form.lead_url' => ...])`.
    |
    */
    'lead_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Filament Integration
    |--------------------------------------------------------------------------
    */
    'filament' => [
        'scoped_to_tenant' => true,
        'navigation_group' => 'Forms',
        'register_forms_resource' => true,
        'register_submissions_resource' => true,
    ],

];
