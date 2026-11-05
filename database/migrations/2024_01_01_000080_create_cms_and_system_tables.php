<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('title_ru')->nullable();
            $table->longText('body')->nullable();
            $table->longText('body_ru')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->string('template', 64)->default('default');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 64)->default('general')->index();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type', 32)->default('string'); // string|json|boolean|integer
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('disk', 32)->default('public');
            $table->string('path');
            $table->string('filename');
            $table->string('mime_type', 128)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('alt')->nullable();
            $table->nullableMorphs('mediable');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 64)->index();
            $table->nullableMorphs('subject');
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_email')->nullable();
            $table->text('message')->nullable();
            $table->string('status', 16)->default('pending')->index(); // pending|approved|rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
        });

        Schema::create('click_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source', 64)->nullable()->index(); // visit|coupon|compare|advisor|widget
            $table->string('referrer')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('destination_url');
            $table->timestamps();

            $table->index('created_at');
            $table->index(['company_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('click_logs');
        Schema::dropIfExists('claims');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('media');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('pages');
    }
};
