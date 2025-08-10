<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MessageReadStatus;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Devuelve el recuento de mensajes no leídos para el usuario autenticado.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Calcula el total de mensajes no leídos para el usuario autenticado.
        // Reglas:
        // - Solo cuenta mensajes pertenecientes a hilos en los que el usuario participa.
        // - Excluye mensajes enviados por el propio usuario.
        // - Un mensaje se considera “no leído” si no existe un registro correspondiente
        //   en la tabla message_read_status para el par (message_id, user_id).
        // Implementación:
        // - Se filtra por los IDs de hilos del usuario.
        // - Se excluyen mensajes del mismo usuario.
        // - Se usa whereNotExists para verificar eficientemente la ausencia de lectura.
        // Resultado: total de mensajes no leídos e hilos.
        $threadIds = DB::table('participants')
            ->where('user_id', $user->id)
            ->pluck('thread_id');

        // Conteo de no leídos por hilo + subject del hilo
        $perThread = DB::table('messages as m')
            ->join('threads as t', 't.id', '=', 'm.thread_id')
            ->whereIn('m.thread_id', $threadIds)
            ->where('m.user_id', '!=', $user->id)
            ->whereNotExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('message_read_status')
                    ->whereColumn('message_read_status.message_id', 'm.id')
                    ->where('message_read_status.user_id', $user->id);
            })
            ->groupBy('m.thread_id', 't.subject')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->get([
                'm.thread_id as thread_id',
                't.subject as subject',
                DB::raw('COUNT(*) as unread_count'),
            ]);

        // Total de no leídos (suma de todos los hilos)
        $totalUnread = $perThread->sum('unread_count');

        return response()->json([
            'unread_messages_count' => $totalUnread,
            'threads' => $perThread,
        ]);

    }

    /**
     * Marca todos los mensajes no leídos de un hilo como leídos para el usuario autenticado.
     *
     * @param Request $request
     * @param Thread $thread
     * @return JsonResponse
     */
    public function markAsRead(Request $request, Thread $thread): JsonResponse
    {
        $user = $request->user();

        // Verificamos que el usuario sea participante del hilo.
        if (!$user->threads()->where('threads.id', $thread->id)->exists()) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        // Obtienen los IDs de los mensajes no leídos por el usuario en el hilo.
        $unreadMessagesIds = $thread->messages()
            ->where('user_id', '!=', $user->id)
            ->whereDoesntHave('readStatus', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->pluck('id');

        // Si no hay mensajes, terminamos.
        if ($unreadMessagesIds->isEmpty()) {
            return response()->json(['message' => 'No hay mensajes nuevos para marcar como leídos.']);
        }

        // Preparamos los datos para una inserción masiva.
        $statusesToInsert = $unreadMessagesIds->map(function ($messageId) use ($user) {
            return [
                'message_id' => $messageId,
                'user_id' => $user->id,
                'read_at' => Carbon::now(),
            ];
        });

        // Insertamos todos los registros de una sola vez.
        MessageReadStatus::insert($statusesToInsert->toArray());

        return response()->json(['message' => 'Mensajes marcados como leídos exitosamente.']);
    }
}
