<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Participant;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThreadAndMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Crear usuarios de prueba
        $mainUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@message-app.com',
            'password' => bcrypt('password'),
        ]);

        $jane = User::factory()->create(['name' => 'Jane Doe']);
        $peter = User::factory()->create(['name' => 'Peter Doe']);

        // Conversación 1: Hilo de Jane y Bob
        Thread::factory()
            ->has(
            // Creamos los mensajes del hilo
                Message::factory()
                    // Creamos dos mensajes en el hilo
                    ->count(2)
                    // Usamos la secuencia para alternar el autor del mensaje
                    ->sequence(
                        ['user_id' => $jane->id, 'body' => 'Hola Peter, ¿algún plan para este fin de semana?'],
                        ['user_id' => $peter->id, 'body' => '¡Hola Alice! Pensaba ir al cine, ¿quieres ir?'],
                    )
            )
            ->afterCreating(function (Thread $thread) use ($jane, $peter) {
                // Después de crear al hilo, añadimos los participantes
                Participant::factory()->create(['thread_id' => $thread->id, 'user_id' => $jane->id]);
                Participant::factory()->create(['thread_id' => $thread->id, 'user_id' => $peter->id]);
            })
            ->create([
                'subject' => 'Planes para el fin de semana'
            ]);

        // --- CONVERSACIÓN 2: Hilo grupal ---
        Thread::factory()
            ->has(
                Message::factory()
                    ->count(3)
                    ->sequence(
                        ['user_id' => $mainUser->id, 'body' => 'Hola equipo, he subido unos cambios.'],
                        ['user_id' => $jane->id, 'body' => '¡Genial! Los reviso de inmediato.'],
                        ['user_id' => $peter->id, 'body' => 'Perfecto, yo comenzare con ello.']
                    )
            )
            ->afterCreating(function (Thread $thread) use ($mainUser, $jane, $peter) {
                Participant::factory()->create(['thread_id' => $thread->id, 'user_id' => $mainUser->id]);
                Participant::factory()->create(['thread_id' => $thread->id, 'user_id' => $jane->id]);
                Participant::factory()->create(['thread_id' => $thread->id, 'user_id' => $peter->id]);
            })
            ->create([
                'subject' => 'Avance del proyecto',
            ]);

    }
}
