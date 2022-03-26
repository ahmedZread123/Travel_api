<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\GeneralTrait;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class checkuser extends BaseMiddleware
{

  use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use GeneralTrait;
    public function handle(Request $request, Closure $next , $guard = null)
    {
        if($guard != null){
          auth()->shouldUse($guard);
         
          $token = $request->header('token');
          $request->headers->set('token',(string) $token , true);
          $request->headers->set('Authorization', 'Bearer '.$token , true);
         
          try{
            $user = JWTAuth::parseToken()->authenticate();
            // check lang 
          
              $lang = $request->header('lang');
              if($lang != null){
                $this->setCurrentLang($lang);
              }
        
            // $user = $this->auth->authenticate($request); // check if user is authenticated
          }catch(\Exception $e){
            return $this->returnError($e->getMessage(), [], 400);
          }
        }
       
        
        
        return $next($request);
    }
}
