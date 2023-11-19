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
            $table->string('key');
            $table->string('name');
            $table->string('fe2_link')->nullable();
            $table->string('fe2_user')->nullable();
            $table->string('fe2_pass')->nullable();
            $table->string('fe2_sync_token')->nullable();
            $table->string('fe2_provisioning_user')->nullable();
            $table->string('fe2_provisioning_leader')->nullable();
            $table->string('hiorg_token')->nullable();
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
