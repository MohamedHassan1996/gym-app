<?php

use App\Enums\User\UserStatus;
use App\Enums\User\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Full name of the user
            $table->string('username'); // Full name of the user
            $table->string('phone')->nullable(); // Full name of the user
            $table->string('address')->nullable(); // Full name of the user
            $table->string('email')->unique(); // Email for login/authentication
            $table->string('password'); // Password for authentication
            $table->tinyInteger('role')->default(UserType::CLIENT->value); // Role: 'admin' or 'end_user'
            $table->foreignId('tenant_id')->nullable()
                  ->constrained('tenants')
                  ->onDelete('cascade');
            $table->tinyInteger('status')->default(UserStatus::INACTIVE->value); // 1 = Active, 0 = Inactive () // Links to the tenant (for admins)
            $table->rememberToken(); // For "Remember Me" functionality
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
