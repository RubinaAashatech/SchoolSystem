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
        Schema::create('eca_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('pdf_image')->nullable();
            $table->enum('player_type', ['single', 'multi', 'competitive']);
            $table->boolean('is_active')->default(0)->comment('0 => no, 1 => yes');
            $table->foreignId('eca_head_id')->constrained('extra_curricular_heads')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eca_activities');
    }
};
