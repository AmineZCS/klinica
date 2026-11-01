<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/**
 * Builds a raw body and a matching Paddle-Signature header for the given secret.
 *
 * @param  array<string, mixed>  $payload
 * @return array{0: string, 1: string}
 */
function signedPaddleRequest(array $payload, string $secret): array
{
    $content = json_encode($payload, JSON_THROW_ON_ERROR);
    $ts = '1700000000';
    $h1 = hash_hmac('sha256', $ts.':'.$content, $secret);

    return [$content, "ts={$ts};h1={$h1}"];
}

/**
 * @param  array<string, mixed>  $payload
 */
function postPaddleWebhook(string $content, string $signature)
{
    return test()->call(
        'POST',
        '/webhooks/paddle',
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_PADDLE_SIGNATURE' => $signature,
        ],
        $content,
    );
}

beforeEach(function (): void {
    config(['services.paddle.webhook_secret' => 'test-secret']);
});

it('rejects a webhook with an invalid signature and records nothing', function (): void {
    $payload = ['event_id' => 'evt_1', 'event_type' => 'subscription.updated', 'data' => []];
    [$content] = signedPaddleRequest($payload, 'test-secret');

    postPaddleWebhook($content, 'ts=1700000000;h1=deadbeef')
        ->assertForbidden();

    expect(DB::table('webhook_events')->count())->toBe(0);
});

it('rejects a webhook when no signature header is present', function (): void {
    postPaddleWebhook('{}', '')
        ->assertForbidden();
});

it('processes a valid event once and ignores a retried duplicate', function (): void {
    $payload = ['event_id' => 'evt_dup', 'event_type' => 'subscription.updated', 'data' => []];
    [$content, $signature] = signedPaddleRequest($payload, 'test-secret');

    postPaddleWebhook($content, $signature)->assertOk();
    postPaddleWebhook($content, $signature)->assertOk();

    // Same event_id delivered twice -> recorded (and processed) exactly once.
    expect(DB::table('webhook_events')->where('event_id', 'evt_dup')->count())->toBe(1);
});
