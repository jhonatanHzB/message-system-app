<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Devuelve la información del usuario autenticado.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function userProfile(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
