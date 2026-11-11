<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Models;

use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Madbox99\FilamentContactForm\Database\Factories\ContactSubmissionFactory;
use Override;

/**
 * @property int $id
 * @property int $contact_form_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $company
 * @property string|null $subject
 * @property string $message
 * @property Carbon|null $consented_at
 * @property string|null $lead_type
 * @property int|string|null $lead_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $page_url
 * @property Carbon $created_at
 */
class ContactSubmission extends Model
{
    /** @use HasFactory<ContactSubmissionFactory> */
    use HasFactory;

    protected $table = 'contact_submissions';

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];

    protected static function newFactory(): ContactSubmissionFactory
    {
        return ContactSubmissionFactory::new();
    }

    /**
     * @return BelongsTo<ContactForm, $this>
     */
    public function contactForm(): BelongsTo
    {
        return $this->belongsTo(ContactForm::class)->withTrashed();
    }

    /**
     * Generic tenant relationship used by Filament for automatic scoping.
     *
     * @return BelongsTo<Model, $this>
     */
    public function tenant(): BelongsTo
    {
        /** @var class-string<Model> $tenantModel */
        $tenantModel = (string) config('filament-contact-form.tenant_model', Model::class);
        $foreignKey = (string) config('filament-contact-form.tenant_foreign_key', 'team_id');

        return $this->belongsTo($tenantModel, $foreignKey);
    }

    /**
     * The record a host application created from this submission (e.g. a CRM
     * opportunity). Set it with `attachLead()` from a `ContactFormSubmitted` listener.
     *
     * @return MorphTo<Model, $this>
     */
    public function lead(): MorphTo
    {
        return $this->morphTo();
    }

    public function attachLead(Model $lead): void
    {
        $this->lead()->associate($lead);
        $this->save();
    }

    /**
     * Admin URL of the attached lead, resolved through the `lead_url` config callable.
     */
    public function getLeadUrl(): ?string
    {
        $resolver = config('filament-contact-form.lead_url');

        if (! $resolver instanceof Closure && ! is_callable($resolver)) {
            return null;
        }

        $lead = $this->lead;

        if (! $lead instanceof Model) {
            return null;
        }

        $url = $resolver($lead);

        return is_string($url) && $url !== '' ? $url : null;
    }

    /**
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'consented_at' => 'datetime',
        ];
    }
}
