<?php

namespace Database\Factories;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thread_id' => Thread::factory(),       // Asocia a un nuevo hilo si no se especifica
            'user_id' => User::factory(),           // Asocia a un nuevo usuario si no se especifica
            'body' => $this->faker->paragraph(),    // Genera un párrafo de texto aleatorio
        ];
    }
}
