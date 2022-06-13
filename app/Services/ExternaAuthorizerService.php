<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternaAuthorizerService
{

    const SERVICE_URL = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";

    public function execute(): bool {
        $auth = Http::post(self::SERVICE_URL);
        $auth = json_decode($auth->body());
        return ($auth->message === "Autorizado");
    }

}
