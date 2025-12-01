<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Services\UrlShortener;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller implements HasMiddleware
{
    public function __construct(private readonly UrlShortener $shortener)
    {

    }

    public static function middleware()
    {
        return [
            new Middleware('throttle:5,1', only: ['store'])
        ];
    }

    public function store(UrlRequest $request)
    {
        $url = $this->shortener->shorten($request->validated('original_url'));

        return response()->json([
            'message' => 'URL has been successfully shortened.',
            'code' => $url->short_url,
        ], Response::HTTP_CREATED);
    }


    public function show(Url $url)
    {
        $this->shortener->resolve($url->short_url);

        return redirect()->away($url->original_url);
    }
}
