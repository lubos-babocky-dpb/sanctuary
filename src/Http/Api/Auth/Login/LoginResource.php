<?php
namespace Dpb\Sanctuary\Http\Api\Auth\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class LoginResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        $identity = $this->resource->activeSession?->authenticatable;

        return [
            'uuid' => $this->resource->uuid,
            'name' => $identity?->name ?? null,
            'email' => $identity?->email ?? null,
            'personalId' => $identity?->personal_id ?? null,
            'roles' => $identity?->roles?->pluck('name')->toArray() ?? [],
            'permissions' => $identity?->permissions?->pluck('name')->toArray() ?? []
        ];
    }
}