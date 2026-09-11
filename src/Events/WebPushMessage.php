<?php
declare(strict_types=1);
namespace Dpb\Sanctuary\Events;

use Illuminate\Foundation\Events\Dispatchable;

class WebPushMessage
{
    use Dispatchable;

    public function __construct(
        public readonly string $type,
        public readonly array $data = [],
    ) {}
}