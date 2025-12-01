<?php

use App\Models\Url;
use App\Services\UrlShortener;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('shortens a url', function () {
    $payload = Url::factory()->make()->toArray();

    $url = new UrlShortener()->shorten($payload['original_url']);

    expect($url->short_url)->toHaveLength(6);

    assertDatabaseHas('urls', [
        'original_url' => $payload['original_url'],
        'short_url' => $url->short_url
    ]);
});

it('can resolve a short url', function () {
    $payload = Url::factory()->make()->toArray();

    $url = new UrlShortener()->shorten($payload['original_url']);

    expect($url->short_url)->toHaveLength(6);

    assertDatabaseHas('urls', [
        'original_url' => $payload['original_url'],
        'short_url' => $url->short_url,
        'click_count' => 0
    ]);

    $url = new UrlShortener()->resolve($url->short_url);

    $url->refresh();

    expect($url->click_count)->toEqual(1);
});

it('does not shorten a url twice', function () {
    $payload = Url::factory()->make()->toArray();

    new UrlShortener()->shorten($payload['original_url']);
    new UrlShortener()->shorten($payload['original_url']);

    assertDatabaseCount('urls', 1);
});

it('returns null if url cannot be resolved', function () {
    $url = new UrlShortener()->resolve('invalid-code');

    expect($url)->toBeNull();
});
