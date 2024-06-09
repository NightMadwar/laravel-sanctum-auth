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
        Schema::create('consumed_medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Drug_ID')->constrained('drugs');
            $table->foreignId('User_ID')->constrained('users');
            $table->string('Doctor_Name');
            $table->date('Date_Prescibed')->nullable();
            $table->enum('period',['morning','afternoon','evening']);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumed_medications');
    }
};
