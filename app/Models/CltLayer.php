<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CltLayer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'layup_id',
        'layer_order',
        'thickness',
        'width',
        'angle',
        'grade',
    ];

    public function cltLayup(): BelongsTo
    {
        return $this->belongsTo(CltLayup::class, 'layup_id');
    }
}
