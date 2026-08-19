<?php
namespace Dpb\Sanctuary\Http\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class LoginResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        return [
            'uuid' => $this->resource->uuid,
            'name' => $this->resource->user?->name ?? null,
            'email' => $this->resource->user?->email ?? null,
            'personalId' => $this->resource->user?->personal_id ?? null
        ];
    }
}