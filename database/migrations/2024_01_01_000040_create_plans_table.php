<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->index();
            $table->decimal('price_renewal', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('billing_cycle', 32)->default('monthly'); // monthly|yearly|biennial
            $table->unsignedInteger('space_gb')->nullable();
            $table->string('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->unsignedInteger('sites')->nullable(); // null = unlimited
            $table->string('bandwidth')->nullable();
            $table->string('os')->nullable()->index();
            $table->string('panel')->nullable()->index();
            $table->decimal('score', 3, 1)->nullable()->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->string('affiliate_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'slug']);
        });

        Schema::create('plan_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->timestamps();

            $table->unique(['plan_id', 'feature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_feature');
        Schema::dropIfExists('plans');
    }
};
