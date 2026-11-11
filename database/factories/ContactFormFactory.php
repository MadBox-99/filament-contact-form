<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Madbox99\FilamentContactForm\Enums\FieldVisibility;
use Madbox99\FilamentContactForm\Models\ContactForm;

/**
 * @extends Factory<ContactForm>
 */
final class ContactFormFactory extends Factory
{
    protected $model = ContactForm::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Contact '.Str::random(6);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'title' => 'Get in touch',
            'description' => null,
            'phone_field' => FieldVisibility::Optional,
            'company_field' => FieldVisibility::Optional,
            'subject_field' => FieldVisibility::Hidden,
            'consent_label' => null,
            'thank_you_message' => null,
            'redirect_url' => null,
            'create_lead' => true,
            'notify_emails' => [],
            'is_active' => true,
        ];
    }

    public function inactive(): self
    {
        return $this->state(['is_active' => false]);
    }

    public function withConsent(string $label = 'I accept the privacy policy.'): self
    {
        return $this->state(['consent_label' => $label]);
    }
}
