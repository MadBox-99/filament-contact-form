<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\ContactFormResource;
use Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\ContactSubmissionResource;

final class FilamentContactFormPlugin implements Plugin
{
    public static function make(): self
    {
        return new self;
    }

    public function getId(): string
    {
        return 'filament-contact-form';
    }

    public function register(Panel $panel): void
    {
        $resources = [];

        if ((bool) config('filament-contact-form.filament.register_forms_resource', true)) {
            $resources[] = ContactFormResource::class;
        }

        if ((bool) config('filament-contact-form.filament.register_submissions_resource', true)) {
            $resources[] = ContactSubmissionResource::class;
        }

        if ($resources !== []) {
            $panel->resources($resources);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
