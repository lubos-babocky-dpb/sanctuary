<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;

#[Table('dpb_sanctuary_model_ghostsession')]
#[Fillable(['ghost_id', 'authenticatable_id', 'authenticatable_type', 'token_id', 'logged_in_at', 'logged_out_at'])]
#[WithoutTimestamps]
class GhostSession extends Model
{

    protected function casts(): array
    {
        return [
            'ghost_id' => 'integer',
            'authenticatable_id' => 'integer',
            'token_id' => 'integer',
            'logged_in_at' => 'datetime',
            'logged_out_at' => 'datetime',
        ];
    }

    public function ghost(): BelongsTo
    {
        return $this->belongsTo(Ghost::class);
    }

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function close(): static
    {
        $this->logged_out_at = now();
        $this->save();
        return $this;
    }
}