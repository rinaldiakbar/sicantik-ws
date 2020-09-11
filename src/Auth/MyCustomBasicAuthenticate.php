<?php
namespace App\Auth;

use Cake\Auth\BasicAuthenticate;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\ServerRequest as Request;
use Cake\Http\Response;

class MyCustomBasicAuthenticate extends BasicAuthenticate
{
    public function unauthenticated(Request $request, Response $response)
    {
        throw new UnauthorizedException();
    }
}
