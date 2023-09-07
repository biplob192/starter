<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\BaseController as BaseController;

class DeletableUser extends BaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!$user = User::find($request->route()->id)) {
                // if (!$user = User::find($request->route()->user)) {
                throw new Exception("No record found.", 404);
            }

            if ($user->hasRole('Super_Admin')) {
                return $this->sendError('This user cannot be delete.', '', 401);
            }

            return $next($request);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }
}
