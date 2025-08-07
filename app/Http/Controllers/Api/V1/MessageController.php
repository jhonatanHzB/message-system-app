<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreMessageRequest;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Almacena un nuevo mensaje en un hilo existente.
     *
     * @param StoreMessageRequest $request
     * @param Thread $thread
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request, Thread $thread): JsonResponse
    {
        // Se crea el mensaje en el hilo.
        $message = $thread->messages()->create([
            'body' => $request->validated()['body'],
            'user_id' => $request->user()->id,
        ]);

        // Carga la relación del usuario para la respuesta.
        $message->load('user');

        return response()->json($message, 201);
    }

}
