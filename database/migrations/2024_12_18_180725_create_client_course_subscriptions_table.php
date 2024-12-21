<?php

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
        Schema::create('client_course_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('subcription_date')->nullable();
            $table->decimal('price')->nullable();
            $table->foreignId('client_course_id')->constrained('client_courses')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_course_subscriptions');
    }
};
