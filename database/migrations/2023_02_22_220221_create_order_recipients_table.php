<?php

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
        Schema::create('order_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('second_name', 50)->nullable();
            $table->string('phone', 13);
            $table->string('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_recipients');
    }
};
