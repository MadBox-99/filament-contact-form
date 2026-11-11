<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Pages;

use Filament\Resources\Pages\CreateRecord;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\ContactFormResource;

class CreateContactForm extends CreateRecord
{
    protected static string $resource = ContactFormResource::class;
}
