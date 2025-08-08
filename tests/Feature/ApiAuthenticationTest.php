<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    /**
     * Restaura la base de datos a su estado original después de cada prueba.
     */
    use RefreshDatabase;

    /**
     * Prueba que un usuario puede iniciar sesión con los accesos correctos.
     *
     */
    #[Test]
    public function a_user_can_login_with_correct_credentials(): void
    {
        // Arrange: Creamos el usuario para la prueba.
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Act: Simulamos la acción de petición a la API
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Assert: Verificamos que el resultado es el esperado con la estructura esperada y el status es 200 OK.
        $response->assertOk();
        $response->assertJsonStructure(['access_token', 'token_type']);
    }

    /**
     * Prueba que un usuario no puede iniciar sesión con accesos incorrectos.
     */
    #[Test]
    public function a_user_cannot_login_with_incorrect_credentials(): void
    {
        // Arrange: Creamos el usuario para la prueba.
        $user = User::factory()->create();

        // Act: Hacemos la petición a la API con credenciales incorrectas.
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // Assert: Verificamos el fallo, la respuesta contiene un error en la validación del campo 'email' y el status es 422.
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Prueba que un usuario no autenticado no puede acceder a rutas protegidas.
     */
    #[Test]
    public function an_unauthenticated_user_cannot_access_protected_routes(): void
    {
        // Act: Hacemos una petición a /api/user sin token.
        $response = $this->getJson('/api/user');

        // Assert: Verificamos que la petición fue denegada con un status 401 Unauthorized.
        $response->assertUnauthorized();
    }

    /**
     * Prueba que un usuario autenticado puede obtener su perfil.
     */
    #[Test]
    public function an_authenticated_user_can_get_their_profile(): void
    {
        // Arrange: Creamos un usuario y simulamos su autenticación con Sanctum.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act: Hacemos la petición a la ruta protegida.
        $response = $this->getJson('/api/user');

        // Assert: Verificamos el éxito y que los datos son correctos.
        $response->assertOk();
        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
