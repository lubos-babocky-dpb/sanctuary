<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Repositories;

use Dpb\Sanctuary\Models\Ghost;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Database\Eloquent\Builder;

final class GhostRepository
{
    public function __construct(
        private readonly ConfigRepository $config
    ) {}

    public function findLinkedToUser(string $personalId): ?Ghost
    {
        $column = $this->config->get('sanctuary.ghost_user_identification_column');

        return Ghost::query()
            ->with('user')
            ->whereHas('user', function (Builder $q) use ($column, $personalId) {
                $q->where($column, $personalId);
            })
            ->first();
    }
}