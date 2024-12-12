<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Illuminate\Database\Eloquent\Model;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Dtos\EventDto;
use App\State\EventStateProvider;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\QueryParameter;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Collection;

use App\Enums\TypeTags;


#[ApiResource(
    shortName: 'Event',
    normalizationContext: ['jsonld_embed_code' => true],
    operations: [
        new Get(
            //output: EventDto::class,
            provider: EventStateProvider::class
        ),
        new GetCollection(
            parameters:[
                'name' => new QueryParameter(filter: PartialSearchFilter::class),
                'collaId' => new QueryParameter(filter: EqualsFilter::class),
                'type' => new QueryParameter(filter: EqualsFilter::class),
            ],
            provider: EventStateProvider::class,            
        )
    ],
)]


class Event extends Model
{
    protected $table = 'events';

    /**
    * @var 
    */
    private Collection $tags;

    #[ApiProperty(identifier: true)]
    private int $id_event;

    // Relations
    public function colla(): BelongsTo
    {
        return $this->belongsTo(Colla::class, 'colla_id', 'id_colla');
    }

    public function tags(): ?BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'event_tag', 'event_id', 'tag_id');
    }

    // Functions
    public function getTags(): Collection
    {
        return $this->tags()->where('type', TypeTags::Events()->value())->get();
    }

}

