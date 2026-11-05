<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('title');
            $table->string('title_ru')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ru')->nullable();
            $table->string('discount')->nullable(); // "70% OFF", "$3.99/mo"
            $table->string('affiliate_url')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->boolean('exclusive')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
