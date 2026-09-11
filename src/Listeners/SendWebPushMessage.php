<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Listeners;

use Dpb\Sanctuary\Events\WebPushMessage;
use Dpb\Sanctuary\Services\WebPushService;

class SendWebPushMessage
{
    public function __construct(
        private readonly WebPushService $webPush,
    ) {}

    public function handle(WebPushMessage $message): void
    {
        $this->webPush->send(
            $message->type,
            $message->data,
        );
    }
}