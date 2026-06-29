<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Http\Api\Handshake;

use Illuminate\Foundation\Http\FormRequest;

final class HandshakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{uuid: array<int, string>}
     */
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'string', 'uuid'],
        ];
    }
}