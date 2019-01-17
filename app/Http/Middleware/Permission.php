<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Auth;
class Permission
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
        //Log::debug('permisson middleware');

        if(Auth::check()){//todo:test
            //用户已登录,加载权限菜单
            $uri = $request->getRequestUri();
           //dd($uri);
            //Log::debug('current uri',['uri'=>$uri]);
            $permissions = session('permissions');
            //dd($permissions);
            $allow = false;
            if($permissions){
                foreach ($permissions as $node) {
                    if($node->path == '/' && $uri != '/'){
                        continue;
                    }
                    $pos = strpos($uri, $node->path);
                    if ($pos !== false) {
                        $allow = true;
                        //dd($pos);
                        break;
                    }
                }
            }


            if ($allow == false) {
            //if(true) {
                //Log::debug('allow',['ajax'=>$request->ajax()]);
                //无权限访问

                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    Auth::logout();
                    session()->flush();
                    return redirect()->guest('/login');
                    //return response('无权限访问.', 401);
                    //return redirect('/');
                    //Log::debug('errors 503');
                    //return view('errors.503');
                }
            }
        }
        else {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                Auth::logout();
                session()->flush();
                return redirect()->guest('/login');
            }
        }

        return $next($request);

    }
}
