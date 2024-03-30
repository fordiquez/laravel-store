<?php

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_main')->default(false);
            $table->foreignIdFor(Country::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(State::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(City::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('street');
            $table->string('house', 10);
            $table->string('flat', 10)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
