<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Empresa a la que se le venden o arriendan hilos de la red.
 */
class FiberThirdParty extends Model
{
    protected $fillable = ['name', 'document', 'contact_name', 'phone', 'email', 'notes'];

    public function strands(): HasMany
    {
        return $this->hasMany(FiberStrand::class, 'third_party_id');
    }
}
