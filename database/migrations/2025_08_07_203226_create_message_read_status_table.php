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
        Schema::create('message_read_status', function (Blueprint $table) {
            $table->id();

            // Foreign key a la tabla messages.
            $table->foreignId('message_id')->constrained()->onDelete('cascade');

            // Foreign key a la tabla users.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Añade la columna 'read_at' para almacenar la fecha y hora de lectura
            $table->timestamp('read_at');

            // Un usuario solo puede tener un registro de lectura por mensaje
            $table->unique(['message_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_read_status');
    }
};
