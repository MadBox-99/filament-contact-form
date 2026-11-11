<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Madbox99\FilamentContactForm\Http\Controllers\EmbedScriptController;
use Madbox99\FilamentContactForm\Http\Controllers\PublicFormController;

$middleware = (array) config('filament-contact-form.routes.middleware', ['web']);
$throttle = (array) config('filament-contact-form.routes.throttle', []);
$showPath = (string) config('filament-contact-form.routes.show_path', 'contact-forms/{slug}');
$embedPath = (string) config('filament-contact-form.routes.embed_path', 'embed/contact-forms/{slug}');
$scriptPath = (string) config('filament-contact-form.routes.widget_script_path', 'contact-forms/embed.js');

Route::middleware($middleware)->group(function () use ($showPath, $embedPath, $scriptPath, $throttle): void {
    // Literal routes must be registered before catch-all {slug} routes so
    // that `/contact-forms/embed.js` is not captured by `/contact-forms/{slug}`.
    Route::get($scriptPath, EmbedScriptController::class)
        ->middleware('throttle:'.($throttle['script'] ?? '120,1'))
        ->name('contact-form.widget.script');

    Route::get($embedPath, [PublicFormController::class, 'embed'])
        ->middleware('throttle:'.($throttle['embed'] ?? '60,1'))
        ->name('contact-form.form.embed');

    Route::get($showPath, [PublicFormController::class, 'show'])
        ->middleware('throttle:'.($throttle['show'] ?? '60,1'))
        ->name('contact-form.form.show');
});
