<?php

declare(strict_types=1);

namespace Madbox99\FilamentContactForm;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Madbox99\FilamentContactForm\Contracts\ContactFormTenantResolver;
use Madbox99\FilamentContactForm\Livewire\PublicContactForm;
use Madbox99\FilamentContactForm\Support\EloquentTenantResolver;

final class FilamentContactFormServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-contact-form.php',
            'filament-contact-form'
        );

        $this->app->singleton(ContactFormTenantResolver::class, function (): EloquentTenantResolver {
            /** @var class-string|null $tenantModel */
            $tenantModel = config('filament-contact-form.tenant_model');
            $slugColumn = (string) config('filament-contact-form.tenant_slug_column', 'slug');

            return new EloquentTenantResolver($tenantModel, $slugColumn);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/filament-contact-form.php' => config_path('filament-contact-form.php'),
        ], 'filament-contact-form-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'filament-contact-form-migrations');

        $this->publishes([
            __DIR__.'/../resources/views/' => resource_path('views/vendor/filament-contact-form'),
        ], 'filament-contact-form-views');

        $this->publishes([
            __DIR__.'/../resources/lang/' => lang_path('vendor/filament-contact-form'),
        ], 'filament-contact-form-translations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-contact-form');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-contact-form');

        Livewire::component('filament-contact-form.public-contact-form', PublicContactForm::class);

        if ((bool) config('filament-contact-form.routes.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
    }
}
