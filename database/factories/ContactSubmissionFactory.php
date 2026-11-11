<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Madbox99\FilamentContactForm\Models\ContactForm;
use Madbox99\FilamentContactForm\Models\ContactSubmission;

/**
 * @extends Factory<ContactSubmission>
 */
final class ContactSubmissionFactory extends Factory
{
    protected $model = ContactSubmission::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_form_id' => ContactForm::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company' => fake()->company(),
            'subject' => null,
            'message' => fake()->paragraph(),
        ];
    }
}
