<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Repositories;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

final class AuthenticatableEntityRepository
{
    public function __construct(
        private readonly ConfigRepository $config
    ) {}

    public function findByIdentifier(
        string $value
    ): null|(Authenticatable&Model) {
        return $this->config->get('sanctuary.user_model')::query()
            ->where(
                column: $this->config->get('sanctuary.ghost_user_identification_column'),
                operator: '=',
                value: $value
            )
            ->first();
    }
}