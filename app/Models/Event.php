<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

use Illuminate\Database\Eloquent\Model;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use App\Dtos\EventDto;
use App\State\EventStateProvider;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Laravel\Eloquent\Filter\RangeFilter;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\QueryParameter;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Collection;

use App\Enums\TypeTags;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ApiResource(
    shortName: 'Event',
    normalizationContext: ['groups' => ['event']],
    paginationItemsPerPage: 50,
    operations: [
        new Get(
            uriTemplate: '/event/{id_event}',
            provider: EventStateProvider::class,
            //output: EventDto::class,
        ),
        new GetCollection(
            parameters:[
                'name' => new QueryParameter(filter: PartialSearchFilter::class),
                'collaId' => new QueryParameter(filter: EqualsFilter::class),
                'type' => new QueryParameter(filter: EqualsFilter::class),
            ],
            provider: EventStateProvider::class,            
        ),
    ],
)]

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id_event';

    #[ApiProperty(identifier: true)]
    #[Groups('event')]
    private int $id_event;

    #[Groups('event')]
    private ?Collection $tags;

    #[SerializedName('title')]
    #[Groups('event')]
    private ?string $name;    

    #[Groups('event')]
    private ?\DateTime $start_date;       
    
    #[Groups('event')]
    private ?string $status; 
    
    #[Groups('event')]
    private ?string $type; 
    
    #[Groups('event')]
    private ?string $comments;     

    #[Groups('event')]
    private ?string $address;    
    
    #[Groups('event')]
    private ?int $companions;     

    #[Groups('event')]
    private ?bool $visibility;     


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

