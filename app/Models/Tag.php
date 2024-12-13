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
use ApiPlatform\Metadata\QueryParameter;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'Tag',
    normalizationContext: ['groups' => ['event']],
    operations: [
        new Get(
            read: false,
            write: false
        ),
        new GetCollection(       
            read: false,
            write: false                 
        )
    ],
)]
class Tag extends Model
{
    protected $table = 'tags'; 
    protected $primaryKey = 'id_tag';

    #[Groups('event')]
    #[ApiProperty(identifier: true)]
    private int $id_tag;

    #[Groups('event')]
    private string $name;
    
    #[Groups('event')]
    private string $value;

    #[Groups('event')]
    private int $group;

    #[Groups('event')]
    private string $type;
    

    public function event(): ?BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_tag', 'event_id', 'tag_id');
    }

}