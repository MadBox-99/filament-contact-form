<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use Madbox99\FilamentContactForm\Models\ContactSubmission;
use Override;

class ViewContactSubmission extends ViewRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('openLead')
                ->label(__('filament-contact-form::contact.actions.open_lead'))
                ->icon(Heroicon::ArrowTopRightOnSquare)
                ->url(static fn (ContactSubmission $record): ?string => $record->getLeadUrl())
                ->visible(static fn (ContactSubmission $record): bool => $record->getLeadUrl() !== null),
            DeleteAction::make(),
        ];
    }
}
