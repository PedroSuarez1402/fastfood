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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->nullOnDelete();
            $table->string('codigo_ticket')->unique();
            $table->string('nombre_cliente')->nullable();
            $table->string('telefono_cliente')->nullable();
            $table->enum('estado', ['pendiente', 'en preparación', 'servido', 'pagado'])->default('pendiente');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
