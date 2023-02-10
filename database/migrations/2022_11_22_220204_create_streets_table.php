<?php

use App\Models\City;
use App\Models\State;
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
        Schema::create('streets', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(City::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('old_name')->nullable();
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
        Schema::dropIfExists('streets');
    }
};
