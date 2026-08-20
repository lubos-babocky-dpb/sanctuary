<?php

namespace Dpb\Sanctuary\Models;

use Dpb\Sanctuary\Contracts\SanctuaryAuthenticatable;
use Dpb\Sanctuary\Exceptions\LinkageFailedException;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

#[Table('dpb_sanctuary_model_ghost')]
#[Fillable(['uuid'])]
class Ghost extends Model implements SanctuaryAuthenticatable
{
    use HasApiTokens;
    use AuthenticatableTrait;

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string'
        ];
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(GhostSession::class);
    }

    public function activeSession(): HasOne
    {
        return $this->hasOne(GhostSession::class)
            ->whereNull('logged_out_at')
            ->latest('logged_in_at');
    }

    public function createSession(
        Authenticatable $identity,
    ): GhostSession {
        ($this->activeSession === null)
            || throw new LinkageFailedException('Ghost already has an active session.');

        $this->activeSession = $this->sessions()->create([
            'authenticatable_id' => $identity->getAuthIdentifier(),
            'authenticatable_type' => $identity::class,
            'token_id' => $this->accessToken?->id,
            'logged_in_at' => now(),
        ]);

        return $this->activeSession;
    }
}