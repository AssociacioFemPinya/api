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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Symfony\Component\Serializer\Annotation\SerializedName;

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

class Event extends Model
{
    protected $connection = 'mysql';
    protected $table = 'events';
    protected $primaryKey = 'id_event';

    protected $hidden = [
        'id_event_external', 
        //'colla_id',
        'colla',
        'duration',
        'close_date',
        'updated_at',
        'created_at'
        ];

    protected $visible = [
    ];        

    #[ApiProperty(identifier: true)]
    private int $id_event;

    #[SerializedName('title')]
    private string $name; 

    // Relations
    public function colla(): BelongsTo
    {
        return $this->belongsTo(Colla::class, 'colla_id', 'id_colla');
    }

    public function tags(): ?BelongsToMany  
    {
        return $this->belongsToMany(Tag::class, 'event_tag', 'event_id', 'tag_id');
    }

}
