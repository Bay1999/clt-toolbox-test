<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CltLayup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'name',
        'grade',
        'revision_counter',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'integer',
        'revision_counter' => 'integer',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function cltLayers(): HasMany
    {
        return $this->hasMany(CltLayer::class, 'layup_id');
    }
}
