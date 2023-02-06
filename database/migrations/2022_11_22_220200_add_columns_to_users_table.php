<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('first_name', 50)->after('id');
            $table->string('last_name', 50)->after('first_name');
            $table->date('birth_date')->after('last_name');
            $table->enum('gender', User::$genders)->after('birth_date');
            $table->string('avatar')->after('gender')->nullable();
            $table->enum('status', User::$statuses)->after('avatar')->default('active');
            $table->string('phone')->after('status')->nullable();
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn(['first_name', 'last_name', 'birth_date', 'gender', 'avatar', 'status', 'phone']);
            $table->dropSoftDeletes();
        });
    }
};
