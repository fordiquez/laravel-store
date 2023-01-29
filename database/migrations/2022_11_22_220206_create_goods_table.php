<?php

use App\Models\Category;
use App\Models\Good;
use App\Models\GoodStatus;
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
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_code')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('warning_description')->nullable();
            $table->float('old_price')->nullable();
            $table->float('price');
            $table->unsignedInteger('quantity')->default(0);
            $table->foreignId('status_id')->constrained('good_statuses')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('goods');
    }
};
