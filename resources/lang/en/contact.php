<?php

declare(strict_types=1);

return [
    'thank_you' => 'Thank you!',
    'submission_received' => 'We have received your message and will get back to you soon.',
    'submit' => 'Send message',
    'submitting' => 'Sending...',

    'resource' => [
        'form_singular' => 'Contact form',
        'form_plural' => 'Contact forms',
        'submission_singular' => 'Contact enquiry',
        'submission_plural' => 'Contact enquiries',
    ],

    'sections' => [
        'form_details' => 'Form details',
        'fields' => 'Fields',
        'submission_settings' => 'After submission',
        'crm' => 'CRM & notifications',
        'design' => 'Design',
        'custom_css' => 'Custom CSS',
        'contact' => 'Contact',
        'submission_details' => 'Submission details',
        'embed_direct' => 'Direct link',
        'embed_widget' => 'JS widget',
        'embed_iframe' => 'Iframe',
    ],

    'descriptions' => [
        'fields' => 'Name, email and message are always shown and required.',
        'custom_css' => 'Scoped to the form container. `@import`, `url(javascript:...)` and similar constructs are stripped.',
    ],

    'fields' => [
        'name' => 'Internal name',
        'slug' => 'URL slug',
        'title' => 'Heading',
        'description' => 'Intro text',
        'submit_label' => 'Button label',
        'active' => 'Active',
        'contact_name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'company' => 'Company',
        'subject' => 'Subject',
        'message' => 'Message',
        'consent' => 'Consent',
        'consent_label' => 'Consent checkbox text',
        'consented_at' => 'Consent given at',
        'thank_you_message' => 'Thank-you message',
        'redirect_url' => 'Redirect URL',
        'create_lead' => 'Create CRM lead',
        'notify_emails' => 'Notification emails',
        'add_email' => 'Add email address',
        'custom_css' => 'CSS',
        'submissions_count' => 'Submissions',
        'created_at' => 'Created at',
        'form' => 'Form',
        'lead' => 'Lead',
        'submitted_at' => 'Submitted at',
        'page_url' => 'Source page',
        'ip_address' => 'IP address',
        'user_agent' => 'User agent',
    ],

    'helpers' => [
        'name' => 'Only visible in the admin.',
        'title' => 'Shown above the form. Defaults to the internal name.',
        'consent_label' => 'When filled, visitors must tick this checkbox (e.g. privacy policy) before sending.',
        'redirect_url' => 'Optional. Visitors are sent here after a successful submission.',
        'create_lead' => 'Every enquiry becomes a customer + lead in the CRM.',
        'notify_emails' => 'These addresses receive an email for every enquiry.',
    ],

    'visibility' => [
        'hidden' => 'Hidden',
        'optional' => 'Optional',
        'required' => 'Required',
    ],

    'design' => [
        'primary_color' => 'Primary colour',
        'radius_label' => 'Corner radius',
        'card_label' => 'Card style',
        'input_bg_label' => 'Input background',
        'submit_label' => 'Button alignment',
        'width_label' => 'Max width',
        'radius' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
        'card' => ['flat' => 'Flat', 'bordered' => 'Bordered', 'shadow' => 'Shadow'],
        'input_bg' => ['gray' => 'Gray', 'white' => 'White'],
        'submit' => ['left' => 'Left', 'center' => 'Center', 'full' => 'Full width'],
        'width' => ['narrow' => 'Narrow', 'default' => 'Default', 'wide' => 'Wide'],
    ],

    'actions' => [
        'embed_code' => 'Embed code',
        'close' => 'Close',
        'preview' => 'Preview',
        'open_lead' => 'Open lead',
    ],

    'embed' => [
        'direct_desc' => 'Share this link or use it as a button target.',
        'widget_desc' => 'Recommended. Paste where the form should appear; it resizes automatically.',
        'iframe_desc' => 'Fallback for sites that do not allow scripts.',
    ],

    'errors' => [
        'not_found' => 'Contact form not found.',
        'rate_limit' => 'Too many attempts. Please try again in :seconds seconds.',
    ],

    'mail' => [
        'subject' => 'New enquiry: :form',
        'intro' => 'A new enquiry was submitted through the ":form" contact form.',
    ],
];
