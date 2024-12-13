<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


// #[ApiResource]
class Colla extends Model
{
    protected $table = 'colles';
    protected $primaryKey = 'id_colla';


    #[ApiProperty(identifier: true)]
    private int $id_colla;

    public function events(): ?HasMany
    {
        return $this->hasMany(Event::class, 'colla_id', 'id_colla');
    }

}
