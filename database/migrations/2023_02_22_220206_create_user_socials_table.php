<?php

use App\Enums\UserProvider;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_socials', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('provider', UserProvider::getValues());
            $table->string('provider_id');
            $table->string('provider_token');
            $table->timestamps();

            $table->unique(['provider', 'provider_id']);
        });

        Schema::table('users', fn (Blueprint $table) => $table->string('password')->nullable()->change());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_socials');
        Schema::table('users', fn (Blueprint $table) => $table->string('password')->nullable(false)->change());
    }
};
