<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contact_submissions')) {
            return;
        }

        $tenantKey = (string) config('filament-contact-form.tenant_foreign_key', 'team_id');

        Schema::create('contact_submissions', function (Blueprint $table) use ($tenantKey): void {
            $table->id();
            $table->foreignId('contact_form_id')
                ->constrained('contact_forms')
                ->cascadeOnDelete();
            $table->unsignedBigInteger($tenantKey)->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 64)->nullable();
            $table->string('company')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->timestamp('consented_at')->nullable();
            $table->nullableMorphs('lead');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('page_url')->nullable();
            $table->timestamps();

            $table->index([$tenantKey, 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
