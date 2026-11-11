# Filament Contact Form

Embeddable contact form for Laravel with a [Filament v5](https://filamentphp.com/) admin panel.

Drop a `<script>` tag into any HTML page (WordPress, static site, SPA) and turn every enquiry into a lead in your CRM.

Forked from [`madbox-99/filament-form-builder`](https://github.com/Cegem-360/filament-form-builder): instead of a free-form field builder it ships a fixed contact form (name, email, phone, company, subject, message, consent) that maps cleanly onto CRM records.

## Features

- Filament resources for contact forms and incoming enquiries
- Name, email and message always present; phone, company and subject can be hidden, optional or required per form
- Optional GDPR consent checkbox (timestamp stored on the enquiry)
- `ContactFormSubmitted` event + `$submission->attachLead($model)` to link the CRM record the host app creates
- Email notification to any number of addresses (queued, `Reply-To` set to the visitor)
- Honeypot, per-IP rate limiting, CSP `frame-ancestors` on the iframe page
- Design tokens and scoped custom CSS per form
- Route-served widget JS with ETag cache busting — no publish step
- Pluggable multi-tenancy (`Team`, `Site`, or no tenant)

## Installation

```bash
composer require madbox-99/filament-contact-form
php artisan vendor:publish --tag=filament-contact-form-config
php artisan migrate
```

```php
// config/filament-contact-form.php
'tenant_model' => \App\Models\Team::class,
'tenant_foreign_key' => 'team_id',
```

```php
use Madbox99\FilamentContactForm\FilamentContactFormPlugin;

$panel->plugins([
    FilamentContactFormPlugin::make(),
]);
```

## Embedding

From the form edit page ("Embed code") copy one of:

```html
<div id="fcf-form-{slug}"></div>
<script src="https://your-app.test/contact-forms/embed.js" data-contact-form="{slug}" async></script>
```

```html
<iframe src="https://your-app.test/embed/contact-forms/{slug}" width="100%" height="700" frameborder="0"></iframe>
```

Direct link: `https://your-app.test/contact-forms/{slug}`

## Creating CRM leads

The package stores the enquiry and dispatches `ContactFormSubmitted`. Create your CRM records in a listener and link them back:

```php
use Madbox99\FilamentContactForm\Events\ContactFormSubmitted;

Event::listen(function (ContactFormSubmitted $event): void {
    if (! $event->form->create_lead) {
        return;
    }

    $lead = Lead::create([
        'name' => $event->submission->name,
        'email' => $event->submission->email,
    ]);

    $event->submission->attachLead($lead);
});
```

To show an "Open lead" button on the enquiry, give the package a URL resolver:

```php
config(['filament-contact-form.lead_url' => fn (Model $lead): string => LeadResource::getUrl('edit', ['record' => $lead])]);
```

## Testing

```bash
composer test
```
