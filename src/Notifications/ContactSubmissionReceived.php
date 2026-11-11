<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Madbox99\FilamentContactForm\Models\ContactSubmission;

class ContactSubmissionReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ContactSubmission $submission,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $submission = $this->submission;
        $formName = (string) $submission->contactForm?->name;

        $message = (new MailMessage)
            ->subject(__('filament-contact-form::contact.mail.subject', ['form' => $formName]))
            ->replyTo($submission->email, $submission->name)
            ->line(__('filament-contact-form::contact.mail.intro', ['form' => $formName]))
            ->line(__('filament-contact-form::contact.fields.contact_name').': '.$submission->name)
            ->line(__('filament-contact-form::contact.fields.email').': '.$submission->email);

        foreach (['phone', 'company', 'subject'] as $attribute) {
            if (filled($submission->{$attribute})) {
                $message->line(__('filament-contact-form::contact.fields.'.$attribute).': '.$submission->{$attribute});
            }
        }

        return $message
            ->line(__('filament-contact-form::contact.fields.message').':')
            ->line($submission->message);
    }
}
