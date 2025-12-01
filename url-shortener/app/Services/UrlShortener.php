<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class UrlShortener
{
    public function __construct(public int $length = 6)
    {

    }

    public function shorten(string $url): Url
    {
        return Url::query()->firstOrCreate(
            attributes: ['original_url' => $url],
            values: ['short_url' => $this->generateUniqueCode()]
        );
    }

    public function resolve(string $code): Url|null
    {
        /** @var Url|null $url */
        $url = Url::query()->where('short_url', $code)->first();

        $url?->increment('click_count');

        return $url ?? null;
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::random($this->length);
        } while (Url::query()->where('code', $code)->exists());

        return $code;
    }
}
