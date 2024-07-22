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
        Schema::create('websites', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->string('baseDomain')->nullable()->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('robots')->nullable();
            $table->string('canonical')->nullable();
            $table->string('general')->nullable();
            $table->boolean('googleTag')->default(false);
            $table->boolean('facebookPixel')->default(false);
            $table->json('siteLinks')->nullable();
            $table->json('siteMap')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
