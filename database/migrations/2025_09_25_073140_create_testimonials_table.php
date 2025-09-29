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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('client_name');
            $table->string('client_position')->nullable();
            $table->string('company_name')->nullable();
            $table->string('client_website')->nullable();
            $table->string('client_photo')->nullable();
            $table->text('testimonial_text');
            $table->tinyInteger('rating')->nullable();
            $table->date('date_received')->nullable();
            $table->string('project_type')->nullable();
            $table->text('project_details')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
