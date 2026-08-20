<?php
declare(strict_types=1);
namespace Dpb\Sanctuary\Contracts;

use Dpb\Sanctuary\Models\GhostSession;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface SanctuaryAuthenticatable
{
    public function sessions(): HasMany;

    public function activeSession(): HasOne;

    public function createSession(Authenticatable $identity): GhostSession;
}