<?php

use App\Enums\UserGender;
use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('first_name', 50)->after('id');
            $table->string('last_name', 50)->after('first_name');
            $table->date('birth_date')->after('last_name')->nullable();
            $table->enum('gender', UserGender::getValues())->after('birth_date')->nullable();
            $table->enum('status', UserStatus::getValues())->after('gender')->default('active');
            $table->string('phone')->after('status')->nullable();
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn(['first_name', 'last_name', 'birth_date', 'gender', 'status', 'phone']);
            $table->dropSoftDeletes();
        });
    }
};
