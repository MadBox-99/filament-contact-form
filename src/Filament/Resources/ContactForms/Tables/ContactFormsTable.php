<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Madbox99\FilamentContactForm\Models\ContactForm;

final class ContactFormsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-contact-form::contact.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('filament-contact-form::contact.fields.slug'))
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('filament-contact-form::contact.fields.active'))
                    ->boolean(),
                IconColumn::make('create_lead')
                    ->label(__('filament-contact-form::contact.fields.create_lead'))
                    ->boolean(),
                TextColumn::make('submissions_count')
                    ->label(__('filament-contact-form::contact.fields.submissions_count'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament-contact-form::contact.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label(__('filament-contact-form::contact.actions.preview'))
                    ->icon(Heroicon::Eye)
                    ->color('gray')
                    ->url(static fn (ContactForm $record): string => $record->getPublicUrl())
                    ->openUrlInNewTab()
                    ->visible(static fn (ContactForm $record): bool => $record->is_active),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
