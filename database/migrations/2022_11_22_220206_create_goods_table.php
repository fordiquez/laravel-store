<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Good;
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
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('warning_description')->nullable();
            $table->unsignedDecimal('old_price')->nullable();
            $table->unsignedDecimal('price');
            $table->unsignedInteger('quantity')->default(0);
            $table->enum('status', Good::$statuses);
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
