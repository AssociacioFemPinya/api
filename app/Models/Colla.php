<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource]
class Colla extends Model
{
    protected $connection = 'mysql';
    protected $table = 'colles';

    #[ApiProperty(identifier: true)]
    private int $id_colla;

    public function castellers(): ?HasMany
    {
        return $this->hasMany(Casteller::class, 'colla_id', 'id_colla');
    }

}
