<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('affiliate_url')->nullable();
            $table->unsignedSmallInteger('founded')->nullable();
            $table->string('headquarters')->nullable();
            $table->text('short_description')->nullable();
            $table->text('short_description_ru')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_ru')->nullable();
            $table->longText('expert_review')->nullable();
            $table->longText('expert_review_ru')->nullable();
            $table->json('pros')->nullable();
            $table->json('pros_ru')->nullable();
            $table->json('cons')->nullable();
            $table->json('cons_ru')->nullable();
            $table->json('rating_breakdown')->nullable(); // pricing,features,performance,ease,support
            $table->decimal('overall_score', 3, 1)->default(0)->index();
            $table->unsignedInteger('review_count')->default(0)->index();
            $table->decimal('price_from', 10, 2)->nullable()->index();
            $table->string('price_currency', 3)->default('USD');
            $table->string('status', 32)->default('pending')->index(); // pending|approved|claimed|rejected|suspended
            $table->foreignId('claimed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_recommended')->default(false)->index();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
        });

        // Full-text search on company names and descriptions (MySQL / MariaDB)
        DB::statement('ALTER TABLE companies ADD FULLTEXT INDEX companies_fulltext (name, name_ru, short_description, description)');
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
