<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('country_code', 2)->index();
            $table->string('country_name');
            $table->string('city')->nullable();
            $table->boolean('is_datacenter')->default(true);
            $table->boolean('is_headquarters')->default(false);
            $table->timestamps();

            $table->unique(['company_id', 'country_code', 'city']);
        });

        // Shared feature dictionary (SSD, Free SSL, cPanel, Money-back…)
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->string('icon')->nullable();
            $table->string('group', 64)->nullable()->index(); // hosting, security, support…
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('company_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'feature_id']);
        });

        // Hosting product types: shared, vps, cloud, dedicated, wordpress…
        Schema::create('company_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('company_company_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_type_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['company_id', 'company_type_id'], 'company_type_unique');
        });

        Schema::create('company_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('rank_in_category')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'category_id']);
            $table->index(['category_id', 'rank_in_category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_category');
        Schema::dropIfExists('company_company_type');
        Schema::dropIfExists('company_types');
        Schema::dropIfExists('company_feature');
        Schema::dropIfExists('features');
        Schema::dropIfExists('company_locations');
    }
};
