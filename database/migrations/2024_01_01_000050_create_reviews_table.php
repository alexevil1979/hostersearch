<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 16)->default('user')->index(); // expert|user
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->decimal('rating_overall', 3, 1)->index();
            $table->json('ratings_json')->nullable(); // pricing, features, performance, ease, support
            $table->string('title');
            $table->text('body');
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->string('status', 16)->default('pending')->index(); // pending|approved|rejected
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_verified')->default(false);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->string('locale', 5)->default('en');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->index(['company_id', 'status', 'type']);
        });

        DB::statement('ALTER TABLE reviews ADD FULLTEXT INDEX reviews_fulltext (title, body)');

        Schema::create('review_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_official')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_replies');
        Schema::dropIfExists('reviews');
    }
};
