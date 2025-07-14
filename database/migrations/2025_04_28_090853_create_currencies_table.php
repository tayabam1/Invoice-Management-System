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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('iso', 3);
            $table->string('iso3', 4);
            $table->string('currency_name');
            $table->string('currency_code', 3);
            $table->string('currency_symbol', 10)->nullable();
            $table->string('native_name')->nullable();
            $table->decimal('exchange_rate', 15, 8)->default(1);
            $table->enum('is_cryptocurrency', ['yes', 'no'])->default('no');
            $table->double('usd_price')->nullable();
            $table->integer('no_of_decimal')->default(2);
            $table->string('thousand_separator', 10)->nullable();
            $table->string('decimal_separator', 10)->nullable();
            $table->string('decimals', 10)->default(2);
            $table->enum('currency_position', ['left', 'right', 'left_with_space', 'right_with_space'])->default('left');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
