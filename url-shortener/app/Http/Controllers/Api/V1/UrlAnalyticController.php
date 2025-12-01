<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Services\UrlShortener;
use Illuminate\Http\Request;

class UrlAnalyticController extends Controller
{
    public function __invoke(Url $url)
    {
        return response()->json($url);
    }
}
