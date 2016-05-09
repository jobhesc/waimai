<?php

namespace App\Http\Middleware;

use App\Http\Validator\Signature;
use App\Http\Validator\Token;
use App\User;
use Closure;
use Validator;

class RequestAuth
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
        $validator = Validator::make($request->header(), [
            'clientinfo'       => 'required',
            'token'            => 'token',
            'app-signature'    => 'required|signature',
        ]);

        $validator->after(function($validator) use($request){
            $uri = $request->path();
            $body = $request->getContent();
            $client_info = $request->header('clientinfo');
            $did = array_get(json_decode($client_info, true), 'did');
            $token = $request->header('token');

            $signature_string = $request->header('app-signature');
            if(!Signature::verify($signature_string, $uri, $body, $did, $token)){
                $validator->errors()->add('signature', 'signature claim error');
            }
        });

        if ($validator->fails()) {
            return response('valid fail', 500);
        }

        return $next($request);
    }
}
