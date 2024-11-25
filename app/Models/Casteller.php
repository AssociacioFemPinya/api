<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Illuminate\Database\Eloquent\Model;

#[ApiResource]
class Casteller extends Model
{
    protected $table = 'castellers';

    #[ApiProperty(identifier: true)]
    private int $id_casteller;
}
