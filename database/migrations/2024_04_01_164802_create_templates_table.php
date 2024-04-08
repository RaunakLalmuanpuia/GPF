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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();

            $table->string('purpose')->nullable();
            $table->text('contents')->nullable();
            
            $table->unsignedBigInteger('entry_info_id')->nullable();
            $table->unsignedBigInteger('individual_info_id')->nullable();

            $table->foreign('entry_info_id')->references('id')->on('entry_infos')->onDelete('cascade');
            $table->foreign('individual_info_id')->references('id')->on('individual_infos')->onDelete('cascade');

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
        Schema::dropIfExists('templates');
    }
};
