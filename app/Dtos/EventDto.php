<?php

namespace App\Dtos;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\EventStateProvider;
use App\Dtos\BaseDto;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\QueryParameter;


class EventDto extends BaseDto
{
    //protected $table = 'events';

    public $id;
    public $name;   
    public $startDate;  


}
