<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthorizerService
{
    public function isAuthorized(): bool
    {
        return true;
        return Http::get(env('AUTHORIZER_API'))['message'] === 'Authorized';
    }
}
