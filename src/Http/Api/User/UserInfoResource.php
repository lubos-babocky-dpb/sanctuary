<?php
namespace Dpb\Sanctuary\Http\Api\User;

use Dpb\Sanctuary\Models\Ghost;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Ghost $resource
 */
class UserInfoResource extends JsonResource
{
    public function toArray(
        Request $request
    ) {
        return [
            'uuid' => $this->resource->uuid,
            'name' => $this->resource->user?->name,
            'email' => $this->resource->user?->email,
            'personalId' => $this->resource->user?->personal_id
        ];
    }
}