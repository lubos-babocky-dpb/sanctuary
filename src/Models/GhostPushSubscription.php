<?php

namespace Dpb\Sanctuary\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('dpb_sanctuary_model_ghostpushsubscription')]
#[Fillable(['ghost_id', 'endpoint', 'p256dh', 'auth'])]
class GhostPushSubscription extends Model
{
    public function ghost(): BelongsTo
    {
        return $this->belongsTo(Ghost::class);
    }
}