<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->index();
            $table->decimal('discounted_price', 10, 2)->nullable()->index();
            $table->unsignedBigInteger('quantity')->index();
            $table->foreignId('sub_category_id')->constrained('sub_categories','id')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('sellers','id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};