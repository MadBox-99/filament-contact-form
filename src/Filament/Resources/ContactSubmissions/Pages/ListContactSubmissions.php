<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Pages;

use Filament\Resources\Pages\ListRecords;
use Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\ContactSubmissionResource;

class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;
}
