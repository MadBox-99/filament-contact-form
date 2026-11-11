<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Madbox99\FilamentContactForm\Casts\DesignTokensCast;
use Madbox99\FilamentContactForm\Database\Factories\ContactFormFactory;
use Madbox99\FilamentContactForm\Enums\FieldVisibility;
use Override;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $title
 * @property string|null $description
 * @property FieldVisibility $phone_field
 * @property FieldVisibility $company_field
 * @property FieldVisibility $subject_field
 * @property string|null $consent_label
 * @property string|null $submit_label
 * @property string|null $thank_you_message
 * @property string|null $redirect_url
 * @property bool $create_lead
 * @property list<string>|null $notify_emails
 * @property string|null $custom_css
 * @property bool $is_active
 * @property int $submissions_count
 */
class ContactForm extends Model
{
    /** @use HasFactory<ContactFormFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'contact_forms';

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];

    protected static function newFactory(): ContactFormFactory
    {
        return ContactFormFactory::new();
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
     * @return HasMany<ContactSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ContactSubmission::class);
    }

    public function tenantKey(): int|string|null
    {
        return $this->getAttribute((string) config('filament-contact-form.tenant_foreign_key', 'team_id'));
    }

    public function getPublicUrl(): string
    {
        return route('contact-form.form.show', $this->slug);
    }

    public function getEmbedUrl(): string
    {
        return route('contact-form.form.embed', $this->slug);
    }

    public function getWidgetScriptUrl(): string
    {
        return route('contact-form.widget.script');
    }

    public function getIframeSnippet(): string
    {
        return sprintf(
            '<iframe src="%s" width="100%%" height="700" frameborder="0"></iframe>',
            $this->getEmbedUrl(),
        );
    }

    public function getWidgetSnippet(): string
    {
        return sprintf(
            '<div id="fcf-form-%s"></div>'."\n".'<script src="%s" data-contact-form="%s" async></script>',
            $this->slug,
            $this->getWidgetScriptUrl(),
            $this->slug,
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'phone_field' => FieldVisibility::class,
            'company_field' => FieldVisibility::class,
            'subject_field' => FieldVisibility::class,
            'create_lead' => 'boolean',
            'notify_emails' => 'array',
            'design_tokens' => DesignTokensCast::class,
            'is_active' => 'boolean',
            'submissions_count' => 'integer',
        ];
    }
}
