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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('case_number')->nullable();
            $table->string('case_type'); // مدني - جنائي - اسرة
            $table->string('court')->nullable();
            $table->string('opponent')->nullable(); // الخصم
            $table->date('start_date')->nullable();
            $table->date('next_session')->nullable();
            $table->decimal('case_price',10,2)->default(0); // اتعاب القضية
            $table->text('description')->nullable();
            $table->enum('status',['pending','open','closed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
