<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Pages\ListContactSubmissions;
use Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Pages\ViewContactSubmission;
use Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Schemas\ContactSubmissionInfolist;
use Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Tables\ContactSubmissionsTable;
use Madbox99\FilamentContactForm\Models\ContactSubmission;
use Override;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

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
        return __('filament-contact-form::contact.resource.submission_singular');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('filament-contact-form::contact.resource.submission_plural');
    }

    #[Override]
    public static function canCreate(): bool
    {
        return false;
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return ContactSubmissionInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return ContactSubmissionsTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListContactSubmissions::route('/'),
            'view' => ViewContactSubmission::route('/{record}'),
        ];
    }
}
