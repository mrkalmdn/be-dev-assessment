<?php

use App\Services\UrlShortener;
use function Pest\Laravel\getJson;

it('shows the analytics of a URL', function () {
    $url = 'https://google.com';

    $url = new UrlShortener()->shorten($url);

    getJson(route('analytics.show', $url->short_url))
        ->assertOk()
        ->assertJsonStructure(['original_url', 'short_url', 'click_count']);
});

it('shows the number of clicks for a URL', function () {
    $shortener = new UrlShortener();
    $url = $shortener->shorten('https://google.com');

    $count = 5;
    for ($i = 0; $i < $count; $i++) {
        $shortener->resolve($url->short_url);
    }

    getJson(route('analytics.show', $url->short_url))
        ->assertOk()
        ->assertJsonStructure(['original_url', 'short_url', 'click_count'])
        ->assertJsonPath('click_count', $count);
});

it('throws an error if the short_url is invalid', function () {
    getJson(route('analytics.show', 'invalid-code'))
        ->assertNotFound();
});
