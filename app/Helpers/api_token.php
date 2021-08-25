<?php

use Illuminate\Support\Facades\Auth;

function api_token()
{
    return Auth()->user()->api_token;
}
