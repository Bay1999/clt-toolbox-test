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
        Schema::create('clt_layers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layup_id')->constrained('clt_layups')->onDelete('cascade');
            $table->string('layer_order');
            $table->string('thickness');
            $table->string('width');
            $table->string('angle');
            $table->string('grade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clt_layers');
    }
};
