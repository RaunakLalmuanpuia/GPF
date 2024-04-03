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
        Schema::create('entry_infos', function (Blueprint $table) {
            $table->id();

            
            
            $table->string('file_number')->nullable();
            $table->string('status')->nullable();
            $table->string('amount')->nullable();
            $table->string('date')->nullable();
            
            $table->unsignedBigInteger('signatory_id')->nullable();
            $table->foreign('signatory_id')->references('id')->on('signatories');

            $table->string('param1')->nullable();
            $table->string('param2')->nullable();
            $table->string('param3')->nullable();
            $table->string('param4')->nullable();
            $table->string('param5')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_infos');
    }
};
