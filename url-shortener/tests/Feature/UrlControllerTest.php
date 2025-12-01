<?php

use App\Models\Url;
use App\Services\UrlShortener;
use Illuminate\Support\Facades\RateLimiter;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('it shortens a url', function () {
    $payload = Url::factory()->make()->toArray();

    postJson(route('urls.store'), $payload)
        ->assertCreated()
        ->assertJsonStructure(['code', 'message']);

    assertDatabaseHas('urls', [
        'original_url' => $payload['original_url']
    ]);
});

it('it redirects to the original url', function () {
    $url = 'https://google.com';

    $url = new UrlShortener()->shorten($url);

    getJson(route('urls.show', $url->short_url))
        ->assertRedirect($url->orignal_url);

    $url->refresh();

    expect($url->click_count)->toEqual(1);
});

it('throws an error if the rate limit is exceeded', function () {
    $payload = Url::factory()->make()->toArray();

    for ($i = 0; $i < 5; $i++) {
        postJson(route('urls.store'), $payload)
            ->assertCreated();
    }

    postJson(route('urls.store'), $payload)
        ->assertTooManyRequests();
});

it('throws an error if the payload is invalid', function () {
    postJson(route('urls.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['original_url']);
});

it('throws an error if the short_url is invalid', function () {
    getJson(route('urls.show', 'invalid-code'))
        ->assertNotFound();
});
