<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreThreadRequest;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->select('threads.*')
            ->selectRaw('? as current_user_id', [$user->id])
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

    /**
     *  Crea un nuevo hilo con su primer mensaje y participantes
     */
    public function store(StoreThreadRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Se usa la transacción para garantizar la integridad de los datos.
        $thread = DB::transaction(function () use ($validated, $request) {

            // Creación del hilo.
            $thread = Thread::create(['subject' => $validated['subject']]);

            // Creación del mensaje inicial.
            $thread->messages()->create([
                'body' => $validated['body'],
                'user_id' => $request->user()->id,
            ]);

            // Añadir participantes al hilo.
            // Se incluye al creador y válida no haya duplicados.
            $participantIds = collect($validated['participants'])
                ->push($request->user()->id)
                ->unique();

            $thread->participants()->attach($participantIds);

            return $thread;
        });

        // Carga las relaciones para devolver a la respuesta.
        $thread->load(['participants', 'latestMessage.user']);

        return response()->json($thread, 201);
    }
}
