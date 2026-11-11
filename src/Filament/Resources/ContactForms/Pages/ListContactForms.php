<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\ContactFormResource;
use Override;

class ListContactForms extends ListRecords
{
    protected static string $resource = ContactFormResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
