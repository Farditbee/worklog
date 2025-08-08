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
        Schema::create('worklog', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('project_id')->constrained('project')->onDelete('cascade');
            $table->text('sebelum');
            $table->text('sesudah');
            $table->text('keterangan')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worklog');
    }
};
