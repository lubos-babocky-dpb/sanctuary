<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Services;

use Illuminate\Support\Facades\DB;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    public function send(string $type, array $data = []): void
    {
        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('services.webpush.subject'),
                'publicKey' => config('services.webpush.public_key'),
                'privateKey' => config('services.webpush.private_key'),
            ],
        ]);

        $subscriptions = DB::table(
            'dpb_sanctuary_model_ghostpushsubscription'
        )->get();

        foreach ($subscriptions as $pushSubscription) {
            $subscription = Subscription::create([
                'endpoint' => $pushSubscription->endpoint,
                'keys' => [
                    'p256dh' => $pushSubscription->p256dh,
                    'auth' => $pushSubscription->auth,
                ],
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode([
                    'type' => $type,
                    ...$data,
                ], JSON_THROW_ON_ERROR)
            );
        }

        foreach ($webPush->flush() as $report) {
            if ($report->isSubscriptionExpired()) {
                DB::table('dpb_sanctuary_model_ghostpushsubscription')
                    ->where('endpoint', $report->getEndpoint())
                    ->delete();
            }
        }
    }
}