<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiNotificationTest extends TestCase
{
    /**
     * Restaura la base de datos a su estado original después de cada prueba.
     */
    use RefreshDatabase;

    /**
     * Prueba que el endpoint de notificaciones cuenta los mensajes no leídos.
     */
    #[Test]
    public function it_counts_unread_messages(): void
    {
        // Arrange: Creamos dos usuarios y un hilo.
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $thread = Thread::factory()->create();

        // Agregamos los usuarios al hilo.
        $thread->participants()->sync([$userA->id, $userB->id]);

        // userB envía 2 mensajes en el hilo. Para userA, estos son mensajes no leídos.
        $thread->messages()->createMany([
            ['user_id' => $userB->id, 'body' => 'Mensaje 1'],
            ['user_id' => $userB->id, 'body' => 'Mensaje 2'],
        ]);

        // Act: Actuamos como userA y consultamos las notificaciones.
        Sanctum::actingAs($userA);
        $response = $this->getJson('/api/notifications');

        // Assert: Verificamos que el resultado es el esperado.
        $response->assertOk();
        $response->assertJson(['unread_messages_count' => 2]);
    }

    /**
     * Prueba el ciclo completo: ver notificaciones, leer mensajes y ver notificaciones de nuevo.
     */
    #[Test]
    public function notification_count_updates_after_reading_messages(): void
    {
        // Arrange: Misma configuración que la prueba anterior.
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $thread = Thread::factory()->create();

        // Agregamos los usuarios al hilo.
        $thread->participants()->sync([$userA->id, $userB->id]);

        // userB envía un mensaje en el hilo.
        $thread->messages()->createMany([
            ['user_id' => $userB->id, 'body' => 'Mensaje de prueba'],
        ]);

        // Act & Assert: Verificamos el primer conteo
        Sanctum::actingAs($userA);
        $this->getJson('/api/notifications')
            ->assertOk()
            ->assertJson(['unread_messages_count' => 1]);

        // Act: Marcamos los mensajes como leídos
        $this->postJson("/api/threads/{$thread->id}/read")
            ->assertOk();

        // Assert: Verificar que el conteo se actualizó a cero
        $this->getJson('/api/notifications')
            ->assertOk()
            ->assertJson(['unread_messages_count' => 0]);
    }

    /**
     * Prueba que un usuario no puede marcar mensajes como leídos en un hilo que no le pertenece.
     */
    #[Test]
    public function a_user_cannot_mark_messages_as_read_in_a_thread_they_do_not_own(): void
    {
        // Arrange: Creamos un hilo entre userA y userB, y un tercer usuario no relacionado.
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $rogueUser = User::factory()->create();
        $thread = Thread::factory()->create();

        // Agregamos los usuarios userA y userB al hilo.
        $thread->participants()->sync([$userA->id, $userB->id]);

        // Act: El usuario no relacionado intenta marcar los mensajes como leídos.
        Sanctum::actingAs($rogueUser);
        $response = $this->postJson("/api/threads/{$thread->id}/read");

        // Assert: La petición debe ser denegada con un status 403 Forbidden.
        $response->assertForbidden();
    }
}
