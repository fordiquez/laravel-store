<?php

use App\Models\Country;
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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('capital');
            $table->string('flag')->nullable();
            $table->char('iso2')->unique();
            $table->char('iso3')->unique();
            $table->char('phone_code');
            $table->char('currency_code');
            $table->char('tld');
            $table->enum('region', Country::$regions);
            $table->enum('subregion', Country::getSubregions())->nullable();
            $table->string('timezone');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
