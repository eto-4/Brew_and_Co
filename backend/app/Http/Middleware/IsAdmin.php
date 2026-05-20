<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware que restringeix l'accés només a usuaris administradors.
 *
 * Comprova que l'usuari autenticat existeixi i tingui permisos d'administrador
 * abans de permetre continuar amb la petició.
 */
class IsAdmin
{
    /**
     * Gestiona la validació d'accés per a rutes protegides d'administrador.
     *
     * Si l'usuari no està autenticat o no és administrador, retorna una resposta
     * HTTP 403. En cas contrari, permet continuar amb la petició.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Accés denegat.',
            ], 403);
        }

        return $next($request);
    }
}