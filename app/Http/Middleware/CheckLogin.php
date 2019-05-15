<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty($_COOKIE['token']) || empty($_COOKIE['uid']) ){
            header('Refresh:2;url=http://passport.1809a.com/login');
            die('请先登陆');
        }
        return $next($request);
    }
}
