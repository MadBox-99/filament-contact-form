<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ContactSubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament-contact-form::contact.sections.contact'))
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('filament-contact-form::contact.fields.contact_name')),
                        TextEntry::make('email')
                            ->label(__('filament-contact-form::contact.fields.email'))
                            ->copyable(),
                        TextEntry::make('phone')
                            ->label(__('filament-contact-form::contact.fields.phone'))
                            ->placeholder('—'),
                        TextEntry::make('company')
                            ->label(__('filament-contact-form::contact.fields.company'))
                            ->placeholder('—'),
                        TextEntry::make('subject')
                            ->label(__('filament-contact-form::contact.fields.subject'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('message')
                            ->label(__('filament-contact-form::contact.fields.message'))
                            ->prose()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make(__('filament-contact-form::contact.sections.submission_details'))
                    ->schema([
                        TextEntry::make('contactForm.name')
                            ->label(__('filament-contact-form::contact.fields.form')),
                        TextEntry::make('created_at')
                            ->label(__('filament-contact-form::contact.fields.submitted_at'))
                            ->dateTime(),
                        TextEntry::make('consented_at')
                            ->label(__('filament-contact-form::contact.fields.consented_at'))
                            ->dateTime()
                            ->placeholder('—'),
                        TextEntry::make('page_url')
                            ->label(__('filament-contact-form::contact.fields.page_url'))
                            ->placeholder('—'),
                        TextEntry::make('ip_address')
                            ->label(__('filament-contact-form::contact.fields.ip_address'))
                            ->placeholder('—'),
                        TextEntry::make('user_agent')
                            ->label(__('filament-contact-form::contact.fields.user_agent'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
