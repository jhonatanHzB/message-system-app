<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        throw ValidationException::withMessages([
            'email' => ['Los datos proporcionadas son incorrectos.'],
        ]);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
});

// Rutas protegidas
// Solo pueden ser accedidas por usuarios autenticados
Route::middleware('auth:sanctum')->group(function () {

    // GET /api/user -> Muestra el perfil del usuario autenticado.
    Route::get('/user', [AuthController::class, 'userProfile']);

    // ===== Rutas de Conversaciones
    // GET /api/threads -> Lista de hilos (paginada, filtros, búsqueda).
    Route::get('/threads', [ThreadController::class, 'index']);

    // POST /api/threads -> Crea nuevo hilo con primer mensaje.
    Route::post('/threads', [ThreadController::class, 'store']);

    // GET /api/threads/{id} -> Detalles del hilo + mensajes
    Route::get('/threads/{thread}', [ThreadController::class, 'show']);

    // ===== Rutas de Mensajes (Reply)
    // GET /api/threads/{thread}/messages -> Enviá respuesta en hilo.
    Route::post('/threads/{thread}/messages', [MessageController::class, 'store']);
});
