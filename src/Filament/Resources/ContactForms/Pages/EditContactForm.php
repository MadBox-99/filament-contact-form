<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm\Filament\Resources\ContactForms\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Madbox99\FilamentContactForm\Filament\Resources\ContactForms\ContactFormResource;
use Madbox99\FilamentContactForm\Models\ContactForm;
use Override;

class EditContactForm extends EditRecord
{
    protected static string $resource = ContactFormResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('embedCode')
                ->label(__('filament-contact-form::contact.actions.embed_code'))
                ->color('info')
                ->modalHeading(__('filament-contact-form::contact.actions.embed_code'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel(__('filament-contact-form::contact.actions.close'))
                ->schema(fn (): array => $this->getEmbedCodeSchema()),
            DeleteAction::make(),
        ];
    }

    /**
     * @return array<int, Section>
     */
    private function getEmbedCodeSchema(): array
    {
        /** @var ContactForm $record */
        $record = $this->getRecord();

        return [
            Section::make(__('filament-contact-form::contact.sections.embed_direct'))
                ->description(__('filament-contact-form::contact.embed.direct_desc'))
                ->schema([
                    Textarea::make('direct_link')
                        ->label(__('filament-contact-form::contact.sections.embed_direct'))
                        ->default($record->getPublicUrl())
                        ->readOnly()
                        ->rows(1)
                        ->extraAttributes(['class' => 'font-mono text-sm'])
                        ->columnSpanFull(),
                ]),
            Section::make(__('filament-contact-form::contact.sections.embed_widget'))
                ->description(__('filament-contact-form::contact.embed.widget_desc'))
                ->schema([
                    Textarea::make('widget_code')
                        ->label(__('filament-contact-form::contact.sections.embed_widget'))
                        ->default($record->getWidgetSnippet())
                        ->readOnly()
                        ->rows(3)
                        ->extraAttributes(['class' => 'font-mono text-sm'])
                        ->columnSpanFull(),
                ]),
            Section::make(__('filament-contact-form::contact.sections.embed_iframe'))
                ->description(__('filament-contact-form::contact.embed.iframe_desc'))
                ->schema([
                    Textarea::make('iframe_code')
                        ->label(__('filament-contact-form::contact.sections.embed_iframe'))
                        ->default($record->getIframeSnippet())
                        ->readOnly()
                        ->rows(3)
                        ->extraAttributes(['class' => 'font-mono text-sm'])
                        ->columnSpanFull(),
                ]),
        ];
    }
}
