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
        Schema::create('user_educations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('institution_name'); // university/school name
            $table->string('degree'); // degree name
            $table->string('field_of_study')->nullable(); // major/field
            $table->enum('education_level', ['high_school', 'associate', 'bachelor', 'master', 'doctorate', 'diploma', 'certificate', 'professional'])->default('bachelor');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false); // currently studying
            $table->string('grade')->nullable(); // GPA or grade
            $table->text('description')->nullable();
            $table->text('activities')->nullable(); // extracurricular activities
            $table->string('location')->nullable();
            $table->string('website')->nullable(); // institution website
            $table->string('logo')->nullable(); // institution logo
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_educations');
    }
};
