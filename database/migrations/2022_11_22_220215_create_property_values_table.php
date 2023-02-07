<?php

use App\Models\Property;
use App\Models\Good;
use App\Models\PropertyValue;
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
        Schema::create('property_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Property::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('good_property_value', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Good::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(PropertyValue::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('good_property_value');

        Schema::dropIfExists('property_values');
    }
};
