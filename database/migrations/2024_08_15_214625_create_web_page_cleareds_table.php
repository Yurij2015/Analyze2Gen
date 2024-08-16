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
        Schema::create('web_page_cleareds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_page_id')->constrained()->cascadeOnDelete();
            $table->text('page_title')->nullable();
            $table->integer('level')->nullable();
            $table->string('node_name')->nullable();
            $table->integer('content_length')->nullable();
            $table->text('base_url')->nullable();
            $table->json('child_nodes')->nullable();
            $table->string('parent_node')->nullable();
            $table->string('content_title')->nullable();
            $table->text('content')->nullable();
            $table->integer('node_count')->nullable();
            $table->timestamps();

            $table->unique(['web_page_id', 'level', 'node_name', 'content_length', 'parent_node', 'node_count'], 'wp_cleared_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_page_cleareds');
    }
};
