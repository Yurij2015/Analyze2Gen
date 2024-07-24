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
        Schema::create('web_pages', static function (Blueprint $table) {
            $table->id();
            $table->string('pageUrl')->nullable()->unique();
            $table->foreignId('website_id')->constrained();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('html')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_pages');
    }
};
