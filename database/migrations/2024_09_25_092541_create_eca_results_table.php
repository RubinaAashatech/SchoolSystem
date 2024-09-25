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
        Schema::create('eca_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eca_participation_id');
            $table->enum('result_type', ['first', 'second', 'third']);
            $table->text('description')->nullable();
            $table->boolean('is_publish')->default(false);
            $table->foreign('eca_participation_id')->references('id')->on('eca_participations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eca_results');
    }
};
