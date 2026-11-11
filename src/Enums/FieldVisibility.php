<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Visibility of an optional contact form field (phone, company, subject).
 * Name, email and message are always shown and required.
 */
enum FieldVisibility: string implements HasLabel
{
    case Hidden = 'hidden';
    case Optional = 'optional';
    case Required = 'required';

    public function getLabel(): string
    {
        return __('filament-contact-form::contact.visibility.'.$this->value);
    }

    public function isVisible(): bool
    {
        return $this !== self::Hidden;
    }

    public function isRequired(): bool
    {
        return $this === self::Required;
    }
}
