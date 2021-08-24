<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\assignApitokenToUsers;

class UserController extends Controller
{
    public function createApiToken()
    {
        $var =  bin2hex(random_bytes(16));
        return $var;
    }
}
