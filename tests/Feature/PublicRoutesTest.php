<?php

declare(strict_types=1);

use Madbox99\FilamentContactForm\Models\ContactForm;
use Madbox99\FilamentContactForm\Models\ContactSubmission;

it('renders the full-page form', function (): void {
    $form = ContactForm::factory()->create(['title' => 'Írjon nekünk']);

    $this->get($form->getPublicUrl())
        ->assertOk()
        ->assertSee('Írjon nekünk');
});

it('serves the iframe page with a `frame-ancestors` CSP', function (): void {
    config(['filament-contact-form.embed.frame_ancestors' => 'https://example.com']);

    $form = ContactForm::factory()->create();

    $this->get($form->getEmbedUrl())
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertSee('fcf:resize', false);

    expect($this->get($form->getEmbedUrl())->headers->get('Content-Security-Policy'))
        ->toContain('frame-ancestors https://example.com');
});

it('returns 404 for inactive forms', function (): void {
    $form = ContactForm::factory()->inactive()->create();

    $this->get($form->getPublicUrl())->assertNotFound();
    $this->get($form->getEmbedUrl())->assertNotFound();
});

it('serves the widget script with an `ETag`', function (): void {
    $response = $this->get(route('contact-form.widget.script'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/javascript; charset=utf-8')
        ->assertSee('data-contact-form', false);

    $this->get(route('contact-form.widget.script'), ['If-None-Match' => $response->headers->get('ETag')])
        ->assertStatus(304);
});

it('builds a widget snippet that matches the widget loader', function (): void {
    $form = ContactForm::factory()->create(['slug' => 'kapcsolat']);

    expect($form->getWidgetSnippet())
        ->toContain('id="fcf-form-kapcsolat"')
        ->toContain('data-contact-form="kapcsolat"');
});

it('attaches a lead and resolves its url through the `lead_url` config', function (): void {
    $submission = ContactSubmission::factory()->create();
    $lead = ContactForm::factory()->create();

    config(['filament-contact-form.lead_url' => fn (ContactForm $record): string => 'https://crm.test/leads/'.$record->getKey()]);

    $submission->attachLead($lead);

    expect($submission->fresh()->getLeadUrl())->toBe('https://crm.test/leads/'.$lead->getKey());
});
