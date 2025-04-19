<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('anggotas')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('acaras')->onDelete('cascade');
            $table->dateTime('check_in_time')->nullable();
            $table->enum('status', ['hadir', 'tidak_hadir'])->default('hadir')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};