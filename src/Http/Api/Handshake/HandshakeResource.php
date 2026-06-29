<?php
namespace Dpb\Sanctuary\Http\Api\Handshake;

use Dpb\Sanctuary\Models\Ghost;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class HandshakeResource extends JsonResource
{
    public function __construct(
        Ghost $resource,
        private string $token
    ) {
        parent::__construct($resource);
    }

    public function toArray(
        Request $request
    ): array {
        return [
            'message' => 'Handshake successful',
            'token' => $this->token
        ];
    }
}