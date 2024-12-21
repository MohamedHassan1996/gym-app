<?php

use App\Traits\DatabaseNames;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use DatabaseNames;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->tinyInteger('date_of_birth')->nullable();
            $table->boolean('gender')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            //$table->foreignId('user_id')->constrained($this->getMainDatabaseName().'.users')->onDelete('cascade');
            $table->foreignId('sport_category_id')->constrained('sport_categories')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
