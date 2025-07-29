<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpInfoController extends Controller
{
    public function getIpInfo()
    {
        $response = Http::get(config('services.ip_info.base_url') . '?apiKey=' . config('services.ip_info.api_key'));
        return $response->json();
    }
}
