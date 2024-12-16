<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Illuminate\Database\Eloquent\Model;

#[ApiResource]
class Colla extends Model
{
    protected $connection = 'mysql';
    protected $table = 'colles';

    #[ApiProperty(identifier: true)]
    private int $id_colla;

}
