<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactSubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Madbox99\FilamentContactForm\Models\ContactSubmission;

final class ContactSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(static fn (Builder $query): Builder => $query->with('contactForm:id,name'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-contact-form::contact.fields.contact_name'))
                    ->description(static fn (ContactSubmission $record): ?string => $record->company)
                    ->searchable(['name', 'company']),
                TextColumn::make('email')
                    ->label(__('filament-contact-form::contact.fields.email'))
                    ->copyable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('filament-contact-form::contact.fields.phone'))
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('message')
                    ->label(__('filament-contact-form::contact.fields.message'))
                    ->limit(60)
                    ->tooltip(static fn (ContactSubmission $record): string => $record->message)
                    ->searchable(),
                TextColumn::make('contactForm.name')
                    ->label(__('filament-contact-form::contact.fields.form'))
                    ->badge()
                    ->toggleable(),
                IconColumn::make('lead_id')
                    ->label(__('filament-contact-form::contact.fields.lead'))
                    ->state(static fn (ContactSubmission $record): bool => $record->lead_id !== null)
                    ->boolean()
                    ->url(static fn (ContactSubmission $record): ?string => $record->getLeadUrl()),
                TextColumn::make('created_at')
                    ->label(__('filament-contact-form::contact.fields.submitted_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('contact_form_id')
                    ->label(__('filament-contact-form::contact.fields.form'))
                    ->relationship('contactForm', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
