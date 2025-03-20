<?php

namespace App\Dtos;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\HeaderParameter;
use ApiPlatform\Metadata\QueryParameter;
use App\Models\Event;
use App\ParameterProvers\CollaParameterProvider;
use App\State\EventsStateProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Symfony\Component\Serializer\Annotation\SerializedName;

/*

We can't use EventDto because Event Model conflicts with MySQL: https://github.com/illuminate/database/blob/master/Schema/Builder.php#L229

#[ApiResource(
    shortName: 'Event',
    operations: [
        new Get(
            provider: EventsStateProvider::class,
        ),
        new GetCollection(
            provider: EventsStateProvider::class,
        ),
    ],
)]
#[QueryParameter(key: 'colla_id', filter: EqualsFilter::class,)]
#[QueryParameter(key: 'type', filter: EqualsFilter::class,)]
#[QueryParameter(key: 'visibility', filter: EqualsFilter::class,)]
*/

class --EventDto
{
    public const PARENT_MODEL = '\App\Models\Event';

    public function __construct(
        public ?int $id_event = null,
        public ?int $colla_id = null,
        public ?string $title = '',
        public ?int $type = null,
        public ?bool $visibility = false

    )
    {
    }
    
    public function fromModel(Event $event){
        return new self(
            $event->id_event,
            $event->colla_id,
            $event->name,
            $event->type,
            $event->visibility
        );
    }


}
