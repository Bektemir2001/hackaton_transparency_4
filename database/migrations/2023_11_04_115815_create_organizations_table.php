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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('inn')->nullable();
            $table->text('registration_number')->nullable();
            $table->text('okpo')->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->text('head')->nullable();
            $table->text('country_name')->nullable();
            $table->text('legal_address')->nullable();
            $table->text('postal_address')->nullable();
            $table->text('email')->nullable();
            $table->text('website')->nullable();
            $table->text('phone')->nullable();
            $table->text('contact_person_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
