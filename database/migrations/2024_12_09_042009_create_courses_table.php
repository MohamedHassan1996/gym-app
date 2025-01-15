<?php

use App\Enums\Course\CourseStatus;
use App\Traits\CreatedUpdatedByMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use CreatedUpdatedByMigration;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_at');
            $table->date('end_at');
            $table->text('description')->nullable();
            $table->json('classes')->nullable();
            $table->tinyInteger('is_active')->default(CourseStatus::INACTIVE->value);
            $table->decimal('price', 8, 2);
            //$table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->foreignId('sport_category_id')->constrained('sport_categories')->onDelete('cascade');
            //$this->createdUpdatedByRelationship($table);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
