<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CastellerConfig extends Model
{
    protected $connection = 'mysql';

    protected $table = 'casteller_config';
    protected $primaryKey = 'id_casteller_config';

    // Relations

    public function casteller(): BelongsTo
    {
        return $this->belongsTo(Casteller::class, 'casteller_id', 'id_casteller');
    }

    //getters relations

    public function getCasteller(): Casteller
    {
        return $this->getAttribute('casteller');
    }
}
