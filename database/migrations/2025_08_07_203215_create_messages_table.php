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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Foreign key a la tabla thread. Si el hilo se borra, sus mensajes también.
            $table->foreignId('thread_id')->constrained()->onDelete('cascade');

            // Foreign key a la tabla users. Si el usuario se borra, sus mensajes también.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Contenido del mensaje.
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
