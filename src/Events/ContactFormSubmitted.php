<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Madbox99\FilamentContactForm\Models\ContactForm;
use Madbox99\FilamentContactForm\Models\ContactSubmission;

/**
 * Dispatched after a public contact form submission has been persisted.
 *
 * Host apps listen to this event to create their CRM records. Check
 * `$form->create_lead` and call `$submission->attachLead($record)` so the
 * admin can jump from the submission to the created lead.
 */
final readonly class ContactFormSubmitted
{
    use Dispatchable;

    public function __construct(
        public ContactForm $form,
        public ContactSubmission $submission,
    ) {}
}
