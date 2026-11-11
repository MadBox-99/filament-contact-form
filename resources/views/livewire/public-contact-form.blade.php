@php
    /** @var \Madbox99\FilamentContactForm\Models\ContactForm $form */
    /** @var string $containerId */
    /** @var string $customCss */
    /** @var string $designTokensCss */
    $optionalFields = [
        'phone' => ['visibility' => $form->phone_field, 'type' => 'tel', 'autocomplete' => 'tel', 'width' => 'half'],
        'company' => ['visibility' => $form->company_field, 'type' => 'text', 'autocomplete' => 'organization', 'width' => 'half'],
        'subject' => ['visibility' => $form->subject_field, 'type' => 'text', 'autocomplete' => 'off', 'width' => 'full'],
    ];
@endphp

<div id="{{ $containerId }}" class="fcf-scope">
    @if ($designTokensCss !== '')
        <style>{!! $designTokensCss !!}</style>
    @endif

    @if ($customCss !== '')
        <style>{!! $customCss !!}</style>
    @endif

    @if ($submitted)
        <div class="fcf-card fcf-card--success" role="status">
            <div class="fcf-icon-circle" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h2 class="fcf-heading">{{ __('filament-contact-form::contact.thank_you') }}</h2>
            <p class="fcf-text">
                {{ filled($form->thank_you_message) ? $form->thank_you_message : __('filament-contact-form::contact.submission_received') }}
            </p>
        </div>
    @else
        <div class="fcf-card">
            <div class="fcf-header">
                <h1 class="fcf-title">{{ filled($form->title) ? $form->title : $form->name }}</h1>
                @if (filled($form->description))
                    <p class="fcf-description">{{ $form->description }}</p>
                @endif
            </div>

            @error('form')
                <div class="fcf-alert fcf-alert--error" role="alert">{{ $message }}</div>
            @enderror

            <form wire:submit="submit" class="fcf-form" novalidate>
                {{-- Honeypot: bots fill every field; humans never see this one. --}}
                <div class="fcf-honeypot" aria-hidden="true">
                    <label for="{{ $containerId }}-website">Website</label>
                    <input id="{{ $containerId }}-website" type="text" name="website" tabindex="-1" autocomplete="off" wire:model="website" />
                </div>

                <div class="fcf-field fcf-field--half @error('name') fcf-field--has-error @enderror">
                    <label for="{{ $containerId }}-name" class="fcf-label">
                        {{ __('filament-contact-form::contact.fields.contact_name') }}<span class="fcf-required" aria-hidden="true">*</span>
                    </label>
                    <input id="{{ $containerId }}-name" type="text" class="fcf-input" wire:model.blur="name" autocomplete="name" maxlength="255" required />
                    @error('name') <p class="fcf-error">{{ $message }}</p> @enderror
                </div>

                <div class="fcf-field fcf-field--half @error('email') fcf-field--has-error @enderror">
                    <label for="{{ $containerId }}-email" class="fcf-label">
                        {{ __('filament-contact-form::contact.fields.email') }}<span class="fcf-required" aria-hidden="true">*</span>
                    </label>
                    <input id="{{ $containerId }}-email" type="email" class="fcf-input" wire:model.blur="email" autocomplete="email" inputmode="email" maxlength="255" required />
                    @error('email') <p class="fcf-error">{{ $message }}</p> @enderror
                </div>

                @foreach ($optionalFields as $field => $config)
                    @if ($config['visibility']->isVisible())
                        <div class="fcf-field fcf-field--{{ $config['width'] }} @error($field) fcf-field--has-error @enderror">
                            <label for="{{ $containerId }}-{{ $field }}" class="fcf-label">
                                {{ __('filament-contact-form::contact.fields.'.$field) }}@if ($config['visibility']->isRequired())<span class="fcf-required" aria-hidden="true">*</span>@endif
                            </label>
                            <input
                                id="{{ $containerId }}-{{ $field }}"
                                type="{{ $config['type'] }}"
                                class="fcf-input"
                                wire:model.blur="{{ $field }}"
                                autocomplete="{{ $config['autocomplete'] }}"
                                maxlength="255"
                                @if ($config['visibility']->isRequired()) required @endif
                            />
                            @error($field) <p class="fcf-error">{{ $message }}</p> @enderror
                        </div>
                    @endif
                @endforeach

                <div class="fcf-field @error('message') fcf-field--has-error @enderror">
                    <label for="{{ $containerId }}-message" class="fcf-label">
                        {{ __('filament-contact-form::contact.fields.message') }}<span class="fcf-required" aria-hidden="true">*</span>
                    </label>
                    <textarea id="{{ $containerId }}-message" class="fcf-input fcf-textarea" rows="5" wire:model.blur="message" maxlength="5000" required></textarea>
                    @error('message') <p class="fcf-error">{{ $message }}</p> @enderror
                </div>

                @if (filled($form->consent_label))
                    <div class="fcf-field @error('consent') fcf-field--has-error @enderror">
                        <label class="fcf-checkbox-row" for="{{ $containerId }}-consent">
                            <input id="{{ $containerId }}-consent" type="checkbox" class="fcf-checkbox" wire:model="consent" required />
                            <span class="fcf-checkbox-label">{{ $form->consent_label }}<span class="fcf-required" aria-hidden="true">*</span></span>
                        </label>
                        @error('consent') <p class="fcf-error">{{ $message }}</p> @enderror
                    </div>
                @endif

                <button type="submit" class="fcf-submit" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">{{ filled($form->submit_label) ? $form->submit_label : __('filament-contact-form::contact.submit') }}</span>
                    <span wire:loading wire:target="submit">{{ __('filament-contact-form::contact.submitting') }}</span>
                </button>
            </form>
        </div>
    @endif
</div>
