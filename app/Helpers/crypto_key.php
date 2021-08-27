<?php

use Illuminate\Support\Facades\Auth;

function crypto_key()
{
   for ($i=0; $i < 4; $i++) {
        for ($j=0; $j < 4 ; $j++) {
            $matrix[$i][$j] =bin2hex(random_bytes(1));
        }
    }


    return json_encode(array_merge($matrix,$matrix));
}
