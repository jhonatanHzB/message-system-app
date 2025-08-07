<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Construimos la consulta base para obtener los hilos del usuario.
        $query = $user->threads()
            ->orderBy('updated_at', 'desc')
            ->with(['participants', 'latestMessage.user']);

        // Se aplica el filtro de búsqueda si el parámetro 'search' es enviado.
        if ($request->has('search')) {
            $query->where('subject', 'like', '%' . $request->input('search') . '%');
        }

        // Paginamos los resultados.
        $threads = $query->paginate(15);

        return response()->json($threads);
    }

    /**
     * Muestra los detalles de una conversación específica, incluyendo todos sus mensajes.
     */
    public function show(Request $request, Thread $thread): JsonResponse
    {
        // Verificar si el usuario es participante del hilo.
        $isParticipant = $request->user()
            ->threads()
            ->where('threads.id', $thread->id)
            ->exists();

        if (!$isParticipant) {
            return response()->json(['error' => 'No tienes permiso para ver esta conversación.'], 403);
        }

        $thread->load(['messages.user', 'participants']);

        return response()->json($thread);
    }
}
