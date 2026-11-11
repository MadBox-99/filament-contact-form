<?php

declare(strict_types=1);

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Madbox99\FilamentContactForm\Enums\FieldVisibility;
use Madbox99\FilamentContactForm\Events\ContactFormSubmitted;
use Madbox99\FilamentContactForm\Livewire\PublicContactForm;
use Madbox99\FilamentContactForm\Models\ContactForm;
use Madbox99\FilamentContactForm\Models\ContactSubmission;
use Madbox99\FilamentContactForm\Notifications\ContactSubmissionReceived;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    RateLimiter::clear('contact-form-submit:*');
});

it('stores a submission and dispatches `ContactFormSubmitted`', function (): void {
    Event::fake([ContactFormSubmitted::class]);

    $form = ContactForm::factory()->create(['team_id' => 7]);

    livewire(PublicContactForm::class, ['form' => $form])
        ->set('name', 'Kiss Anna')
        ->set('email', 'Anna@Example.com ')
        ->set('phone', '+36 30 123 4567')
        ->set('company', 'Példa Kft.')
        ->set('message', 'Árajánlatot kérnék.')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('submitted', true)
        ->assertSee('Thank you!');

    $submission = ContactSubmission::query()->sole();

    expect($submission)
        ->team_id->toBe(7)
        ->name->toBe('Kiss Anna')
        ->email->toBe('anna@example.com')
        ->phone->toBe('+36 30 123 4567')
        ->company->toBe('Példa Kft.')
        ->subject->toBeNull()
        ->message->toBe('Árajánlatot kérnék.')
        ->and($form->fresh()->submissions_count)->toBe(1);

    Event::assertDispatched(
        ContactFormSubmitted::class,
        fn (ContactFormSubmitted $event): bool => $event->submission->is($submission) && $event->form->is($form),
    );
});

it('requires name, email and message', function (): void {
    $form = ContactForm::factory()->create();

    livewire(PublicContactForm::class, ['form' => $form])
        ->call('submit')
        ->assertHasErrors(['name' => 'required', 'email' => 'required', 'message' => 'required']);

    expect(ContactSubmission::query()->count())->toBe(0);
});

it('requires fields set to `FieldVisibility::Required`', function (): void {
    $form = ContactForm::factory()->create([
        'phone_field' => FieldVisibility::Required,
        'company_field' => FieldVisibility::Required,
        'subject_field' => FieldVisibility::Required,
    ]);

    livewire(PublicContactForm::class, ['form' => $form])
        ->set('name', 'Kiss Anna')
        ->set('email', 'anna@example.com')
        ->set('message', 'Hello')
        ->call('submit')
        ->assertHasErrors(['phone' => 'required', 'company' => 'required', 'subject' => 'required']);
});

it('ignores values of hidden fields', function (): void {
    $form = ContactForm::factory()->create([
        'phone_field' => FieldVisibility::Hidden,
        'company_field' => FieldVisibility::Hidden,
    ]);

    livewire(PublicContactForm::class, ['form' => $form])
        ->assertDontSee('Phone')
        ->set('name', 'Kiss Anna')
        ->set('email', 'anna@example.com')
        ->set('phone', 'not a phone')
        ->set('company', 'Injected Kft.')
        ->set('message', 'Hello')
        ->call('submit')
        ->assertHasNoErrors();

    expect(ContactSubmission::query()->sole())
        ->phone->toBeNull()
        ->company->toBeNull();
});

it('requires the consent checkbox when a `consent_label` is set', function (): void {
    $form = ContactForm::factory()->withConsent('Elfogadom az adatkezelési tájékoztatót.')->create();

    $component = livewire(PublicContactForm::class, ['form' => $form])
        ->assertSee('Elfogadom az adatkezelési tájékoztatót.')
        ->set('name', 'Kiss Anna')
        ->set('email', 'anna@example.com')
        ->set('message', 'Hello')
        ->call('submit')
        ->assertHasErrors(['consent' => 'accepted']);

    $component->set('consent', true)->call('submit')->assertHasNoErrors();

    expect(ContactSubmission::query()->sole()->consented_at)->not->toBeNull();
});

it('silently drops honeypot submissions', function (): void {
    Event::fake([ContactFormSubmitted::class]);

    $form = ContactForm::factory()->create();

    livewire(PublicContactForm::class, ['form' => $form])
        ->set('website', 'https://spam.example')
        ->set('name', 'Bot')
        ->set('email', 'bot@example.com')
        ->set('message', 'Buy now')
        ->call('submit')
        ->assertSet('submitted', true);

    expect(ContactSubmission::query()->count())->toBe(0);
    Event::assertNotDispatched(ContactFormSubmitted::class);
});

it('rate limits submissions per IP', function (): void {
    config(['filament-contact-form.submission_rate_limit.attempts' => 1]);

    $form = ContactForm::factory()->create();

    $fill = fn () => livewire(PublicContactForm::class, ['form' => $form])
        ->set('name', 'Kiss Anna')
        ->set('email', 'anna@example.com')
        ->set('message', 'Hello')
        ->call('submit');

    $fill()->assertHasNoErrors();
    $fill()->assertHasErrors('form');

    expect(ContactSubmission::query()->count())->toBe(1);
});

it('emails the `notify_emails` recipients', function (): void {
    Notification::fake();

    $form = ContactForm::factory()->create(['notify_emails' => ['sales@example.com', 'not-an-email']]);

    livewire(PublicContactForm::class, ['form' => $form])
        ->set('name', 'Kiss Anna')
        ->set('email', 'anna@example.com')
        ->set('message', 'Hello')
        ->call('submit');

    Notification::assertSentOnDemand(
        ContactSubmissionReceived::class,
        fn (ContactSubmissionReceived $notification, array $channels, AnonymousNotifiable $notifiable): bool => $notifiable->routes['mail'] === ['sales@example.com'],
    );
});

it('returns `null` from `safeRedirectUrl()` for non-http urls', function (?string $url, ?string $expected): void {
    expect(PublicContactForm::safeRedirectUrl($url))->toBe($expected);
})->with([
    [null, null],
    ['javascript:alert(1)', null],
    ['/relative', null],
    ['https://example.com/thanks', 'https://example.com/thanks'],
]);
