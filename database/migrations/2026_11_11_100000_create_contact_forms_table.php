<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contact_forms')) {
            return;
        }

        $tenantKey = (string) config('filament-contact-form.tenant_foreign_key', 'team_id');

        Schema::create('contact_forms', function (Blueprint $table) use ($tenantKey): void {
            $table->id();
            $table->unsignedBigInteger($tenantKey)->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('phone_field', 16)->default('optional');
            $table->string('company_field', 16)->default('optional');
            $table->string('subject_field', 16)->default('hidden');
            $table->text('consent_label')->nullable();
            $table->string('submit_label')->nullable();
            $table->text('thank_you_message')->nullable();
            $table->string('redirect_url', 2048)->nullable();
            $table->boolean('create_lead')->default(true);
            $table->json('notify_emails')->nullable();
            $table->json('design_tokens')->nullable();
            $table->longText('custom_css')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('submissions_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index($tenantKey);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_forms');
    }
};
