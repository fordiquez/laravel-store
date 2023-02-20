<?php

use App\Models\PromoCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->enum('type', PromoCode::$types);
            $table->tinyInteger('value');
            $table->string('description');
            $table->integer('used_times')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->unsignedDecimal('greater_than')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
