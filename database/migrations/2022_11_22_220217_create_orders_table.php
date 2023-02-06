<?php

use App\Models\Order;
use App\Models\PromoCode;
use App\Models\User;
use App\Models\UserAddress;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('ref_id')->unique();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(UserAddress::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(PromoCode::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('delivery_method', Order::$deliveryMethods);
            $table->enum('payment_method', Order::$paymentMethods);
            $table->unsignedDecimal('goods_cost');
            $table->unsignedDecimal('delivery_cost')->nullable()->default(0);
            $table->unsignedDecimal('total_cost');
            $table->enum('status', Order::$statuses)->default('unpaid');
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
        Schema::dropIfExists('orders');
    }
};
