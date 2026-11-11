<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactForms;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Pages\CreateContactForm;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Pages\EditContactForm;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Pages\ListContactForms;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Schemas\ContactFormForm;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Tables\ContactFormsTable;
use Madbox99\FilamentContactForm\Models\ContactForm;
use Override;

class ContactFormResource extends Resource
{
    protected static ?string $model = ContactForm::class;

    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    #[Override]
    public static function getNavigationGroup(): ?string
    {
        $group = config('filament-contact-form.filament.navigation_group');

        return is_string($group) ? $group : null;
    }

    public static function isScopedToTenant(): bool
    {
        return (bool) config('filament-contact-form.filament.scoped_to_tenant', true);
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('filament-contact-form::contact.resource.form_singular');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('filament-contact-form::contact.resource.form_plural');
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return ContactFormForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return ContactFormsTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListContactForms::route('/'),
            'create' => CreateContactForm::route('/create'),
            'edit' => EditContactForm::route('/{record}/edit'),
        ];
    }
}
