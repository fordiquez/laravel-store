<?php

use App\Models\Good;
use App\Models\Option;
use App\Models\OptionValue;
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
        Schema::create('option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Option::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('value', 100);
            $table->timestamps();
        });

        Schema::create('good_option_value', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Good::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(OptionValue::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('good_option_value');

        Schema::dropIfExists('option_values');
    }
};
