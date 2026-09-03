<?php

namespace Dpb\Sanctuary\Http\Api\Push;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController
{
    public function __invoke(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'endpoint'    => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth'   => ['required', 'string'],
        ]);

        $subscription = $request->user()
            ?->pushSubscriptions()
            ?->updateOrCreate(
                ['endpoint' => $validated['endpoint']],
                [
                    'p256dh' => $validated['keys']['p256dh'],
                    'auth'   => $validated['keys']['auth'],
                ]
            );

        return response()->json([
            'success' => true,
            'subscription_id' => $subscription->id,
        ]);
    }
}