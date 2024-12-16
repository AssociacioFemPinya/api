<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Illuminate\Database\Eloquent\Model;

#[ApiResource]
class Event extends Model
{
    protected $connection = 'mysql';
    protected $table = 'events';

    #[ApiProperty(identifier: true)]
    private int $id_event;
}
