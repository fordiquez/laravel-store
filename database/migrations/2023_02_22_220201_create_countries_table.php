<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('short_name', 25)->nullable();
            $table->string('capital');
            $table->char('iso2', 2)->unique();
            $table->char('iso3', 3)->unique();
            $table->char('phone_code');
            $table->char('currency', 3);
            $table->char('tld', 3);
            $table->enum('region', Country::getRegions());
            $table->enum('subregion', Country::getSubregions())->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
