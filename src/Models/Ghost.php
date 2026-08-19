<?php

namespace Dpb\Sanctuary\Models;

use Dpb\Sanctuary\Exceptions\LinkageFailedException;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

#[Table('dpb_sanctuary_model_ghost')]
#[Fillable(['uuid', 'user_id'])]
class Ghost extends Model
{
    use HasApiTokens;
    use AuthenticatableTrait;

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'user_id' => 'integer',
        ];
    }

    public function isLinked(): bool
    {
        return !is_null($this->user_id);
    }

    public function attachIdentity(
        Authenticatable $identity
    ): static {
        if ($this->isLinked()) {
            throw new LinkageFailedException('Ghost is already linked to an identity.');
        }

        $this->user_id = $identity->getAuthIdentifier();

        return $this;
    }
}