<?php

namespace App\Models;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\HeaderParameter;
use ApiPlatform\Metadata\QueryParameter;
use App\ParameterProvers\CollaParameterProvider;
use App\State\EventsStateProvider;
use Illuminate\Database\Eloquent\Model;

#[ApiResource(
    shortName: 'Event',
    operations: [
        new Get(
            provider: EventsStateProvider::class,
        ),
        new GetCollection(
            provider: EventsStateProvider::class,
            //parameters: ['colla_id' => new HeaderParameter(provider: CollaParameterProvider::class)]
            parameters: ['colla' => new QueryParameter(provider: CollaParameterProvider::class)]

        ),
    ],
)]
#[QueryParameter(key: 'colla_id', filter: EqualsFilter::class,)]
class Event extends Model
{
    protected $connection = 'mysql';
    protected $table = 'events';

    #[ApiProperty(identifier: true)]
    private int $id_event;
}
