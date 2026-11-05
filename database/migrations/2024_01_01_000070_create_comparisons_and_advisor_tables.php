<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comparisons', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('title_ru')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ru')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_popular')->default(false)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('comparison_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comparison_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('position')->default(1);
            $table->timestamps();

            $table->unique(['comparison_id', 'company_id']);
        });

        Schema::create('advisor_questions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('question');
            $table->string('question_ru')->nullable();
            $table->string('type', 32)->default('single'); // single|multiple|range
            $table->json('meta')->nullable(); // min/max for range, etc.
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('advisor_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advisor_question_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('label');
            $table->string('label_ru')->nullable();
            $table->json('weights')->nullable(); // {feature_slug: weight, type_slug: weight}
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['advisor_question_id', 'slug'], 'advisor_answer_unique');
        });

        Schema::create('advisor_results', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('answers'); // question_slug => answer_slug|array
            $table->json('recommended_company_ids');
            $table->json('scores')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisor_results');
        Schema::dropIfExists('advisor_answers');
        Schema::dropIfExists('advisor_questions');
        Schema::dropIfExists('comparison_company');
        Schema::dropIfExists('comparisons');
    }
};
