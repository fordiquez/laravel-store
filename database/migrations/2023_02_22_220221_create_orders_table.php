<?php

use App\Enums\OrderDelivery;
use App\Enums\OrderPayment;
use App\Enums\OrderStatus;
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
            $table->enum('delivery_method', OrderDelivery::getValues());
            $table->enum('payment_method', OrderPayment::getValues());
            $table->unsignedDecimal('goods_cost');
            $table->unsignedDecimal('delivery_cost')->nullable()->default(0);
            $table->unsignedDecimal('total_cost');
            $table->enum('status', OrderStatus::getValues())->default(OrderStatus::UNPAID);
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
