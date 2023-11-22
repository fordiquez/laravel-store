<?php

use App\Models\Good;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Good::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_buyer')->default(true);
            $table->text('content');
            $table->tinyText('advantages');
            $table->tinyText('disadvantages');
            $table->unsignedTinyInteger('rating');
            $table->string('video_src')->nullable();
            $table->ipAddress()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
