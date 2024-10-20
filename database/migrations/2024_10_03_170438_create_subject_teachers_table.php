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
        Schema::create('subject_teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_group_id')->nullable();
            $table->foreign('subject_group_id')->references('id')->on('subject_groups')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_teachers');
    }
};
