<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckJsonMiddleware
{

    /**
     * Handle an incoming request and check header.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle( Request $request, Closure $next )
    {

        if ( $request->isMethod( 'get' ) ) return $next( $request );

        $contentTypeHeader = $request->header( 'Content-Type' );
        if ( $contentTypeHeader != 'application/json' ) {
            return response()->json( [], 406 );
        }

        return $next( $request );

    }

}
