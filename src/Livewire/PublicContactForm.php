<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Js;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Madbox99\FilamentContactForm\Enums\FieldVisibility;
use Madbox99\FilamentContactForm\Events\ContactFormSubmitted;
use Madbox99\FilamentContactForm\Models\ContactForm;
use Madbox99\FilamentContactForm\Models\ContactSubmission;
use Madbox99\FilamentContactForm\Notifications\ContactSubmissionReceived;
use Madbox99\FilamentContactForm\Support\CssSanitizer;
use Madbox99\FilamentContactForm\ValueObjects\DesignTokens;

final class PublicContactForm extends Component
{
    /**
     * The form id is the only model reference sent to the client. Settings like
     * `notify_emails`, `redirect_url` and the tenant key never appear in the
     * serialised Livewire snapshot.
     */
    #[Locked]
    public int $formId = 0;

    #[Locked]
    public string $formSlug = '';

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $company = '';

    public string $subject = '';

    public string $message = '';

    public bool $consent = false;

    /**
     * Honeypot. Any non-empty value is treated as bot activity and silently
     * discarded — the UI still renders the "thank you" state.
     */
    public string $website = '';

    /**
     * The URL of the page the form was opened from (the iframe referrer).
     */
    #[Locked]
    public string $pageUrl = '';

    public bool $submitted = false;

    private ?ContactForm $formInstance = null;

    public function mount(ContactForm $form): void
    {
        if (! $form->is_active) {
            abort(404);
        }

        $this->formInstance = $form;
        $this->formId = (int) $form->id;
        $this->formSlug = (string) $form->slug;
        $this->pageUrl = mb_substr((string) request()->headers->get('referer', ''), 0, 2048);
    }

    public function submit(): void
    {
        $form = $this->resolveForm();

        if ($this->website !== '') {
            $this->submitted = true;

            return;
        }

        $attempts = (int) config('filament-contact-form.submission_rate_limit.attempts', 5);
        $decay = (int) config('filament-contact-form.submission_rate_limit.decay_seconds', 60);
        $key = 'contact-form-submit:'.$this->formId.':'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, $attempts)) {
            $this->addError('form', __('filament-contact-form::contact.errors.rate_limit', [
                'seconds' => RateLimiter::availableIn($key),
            ]));

            return;
        }

        RateLimiter::hit($key, $decay);

        foreach (['name', 'email', 'phone', 'company', 'subject', 'message'] as $field) {
            $this->{$field} = trim($this->{$field});
        }

        $validated = $this->validate($this->rules($form), [], $this->validationAttributes());

        $submission = DB::transaction(function () use ($form, $validated): ContactSubmission {
            /** @var ContactSubmission $submission */
            $submission = $form->submissions()->create([
                (string) config('filament-contact-form.tenant_foreign_key', 'team_id') => $form->tenantKey(),
                'name' => trim((string) $validated['name']),
                'email' => mb_strtolower(trim((string) $validated['email'])),
                'phone' => $form->phone_field->isVisible() ? self::nullIfBlank($validated['phone'] ?? null) : null,
                'company' => $form->company_field->isVisible() ? self::nullIfBlank($validated['company'] ?? null) : null,
                'subject' => $form->subject_field->isVisible() ? self::nullIfBlank($validated['subject'] ?? null) : null,
                'message' => trim((string) $validated['message']),
                'consented_at' => filled($form->consent_label) ? now() : null,
                'ip_address' => request()->ip(),
                'user_agent' => mb_substr((string) request()->userAgent(), 0, 1000),
                'page_url' => $this->pageUrl !== '' ? $this->pageUrl : null,
            ]);

            $form->increment('submissions_count');

            return $submission;
        });

        ContactFormSubmitted::dispatch($form, $submission);

        $this->notifyRecipients($form, $submission);

        $this->submitted = true;
        $this->reset('name', 'email', 'phone', 'company', 'subject', 'message', 'consent');

        $redirect = self::safeRedirectUrl($form->redirect_url);

        if ($redirect !== null) {
            $url = Js::from($redirect);

            $this->js(<<<JS
                (function () {
                    var detail = { url: {$url} };
                    try {
                        document.dispatchEvent(new CustomEvent('fcf:redirect', { detail: detail, bubbles: true }));
                        window.dispatchEvent(new CustomEvent('fcf:redirect', { detail: detail }));
                    } catch (e) {}
                    if (window.parent === window) {
                        window.location.href = {$url};
                    }
                })();
                JS);
        }
    }

    public function render(): View
    {
        $form = $this->resolveForm();

        $scopeSelector = '#fcf-form-'.$this->formSlug;
        $customCss = CssSanitizer::sanitize(
            (string) ($form->custom_css ?? ''),
            (int) config('filament-contact-form.custom_css.max_length', CssSanitizer::DEFAULT_MAX_LENGTH),
        );

        $tokens = $form->design_tokens instanceof DesignTokens ? $form->design_tokens : new DesignTokens;
        $declarations = $tokens->toCssDeclarations();

        return view('filament-contact-form::livewire.public-contact-form', [
            'form' => $form,
            'containerId' => 'fcf-form-'.$this->formSlug,
            'customCss' => $customCss !== '' && (bool) config('filament-contact-form.custom_css.enabled', true)
                ? CssSanitizer::scope($customCss, $scopeSelector)
                : '',
            'designTokensCss' => $declarations !== '' ? $scopeSelector.' { '.$declarations.' }' : '',
        ]);
    }

    public static function safeRedirectUrl(mixed $url): ?string
    {
        if (! is_string($url) || $url === '') {
            return null;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if (! is_string($scheme) || ! in_array(strtolower($scheme), ['http', 'https'], true)) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return null;
        }

        return $url;
    }

    /**
     * @return array<string, list<string>>
     */
    private function rules(ContactForm $form): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];

        $optionalFields = [
            'phone' => [$form->phone_field, ['string', 'max:64', 'regex:/^[0-9+\-\s().\/]{5,64}$/']],
            'company' => [$form->company_field, ['string', 'max:255']],
            'subject' => [$form->subject_field, ['string', 'max:255']],
        ];

        foreach ($optionalFields as $field => [$visibility, $fieldRules]) {
            /** @var FieldVisibility $visibility */
            if ($visibility->isVisible()) {
                $rules[$field] = [$visibility->isRequired() ? 'required' : 'nullable', ...$fieldRules];
            }
        }

        if (filled($form->consent_label)) {
            $rules['consent'] = ['accepted'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    private function validationAttributes(): array
    {
        return [
            'name' => __('filament-contact-form::contact.fields.contact_name'),
            'email' => __('filament-contact-form::contact.fields.email'),
            'phone' => __('filament-contact-form::contact.fields.phone'),
            'company' => __('filament-contact-form::contact.fields.company'),
            'subject' => __('filament-contact-form::contact.fields.subject'),
            'message' => __('filament-contact-form::contact.fields.message'),
            'consent' => __('filament-contact-form::contact.fields.consent'),
        ];
    }

    private function notifyRecipients(ContactForm $form, ContactSubmission $submission): void
    {
        $recipients = array_values(array_filter(
            (array) ($form->notify_emails ?? []),
            static fn (mixed $email): bool => is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
        ));

        if ($recipients === []) {
            return;
        }

        Notification::route('mail', $recipients)->notify(new ContactSubmissionReceived($submission));
    }

    private function resolveForm(): ContactForm
    {
        if ($this->formInstance instanceof ContactForm) {
            return $this->formInstance;
        }

        /** @var ContactForm $form */
        $form = ContactForm::query()->findOrFail($this->formId);

        if (! $form->is_active) {
            abort(404);
        }

        $this->formInstance = $form;

        return $form;
    }

    private static function nullIfBlank(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
