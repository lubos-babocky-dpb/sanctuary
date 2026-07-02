<?php
namespace Dpb\Sanctuary\Http\Api\Handshake;

use Carbon\Carbon;
use Dpb\Sanctuary\Models\Ghost;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class HandshakeResource extends JsonResource
{
    public function __construct(
        Ghost $resource,
        private string $token,
        private ?Carbon $expiresAt
    ) {
        parent::__construct($resource);
    }

    public function toArray(
        Request $request
    ): array {
        return [
            'type' => 'bearer',
            'token' => $this->token,
            'expiresAt' => $this->expiresAt?->toIso8601String()
        ];
    }
}