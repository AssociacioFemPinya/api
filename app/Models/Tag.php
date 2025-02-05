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
use App\Dtos\TagDto;
use App\ParameterProvers\CollaParameterProvider;
use App\State\EventsStateProvider;
use App\State\TagsStateProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Symfony\Component\Serializer\Annotation\SerializedName;


#[ApiResource(
    shortName: 'Tag',
    //normalizationContext: ['groups' => ['tags:read']],
    operations: [
        new Get(
            provider: TagsStateProvider::class
        ),
        new GetCollection(
            provider: TagsStateProvider::class,
            parameters: [
                'type'  => new QueryParameter(filter: EqualsFilter::class),
            ]            
        ),
    ],
)]


class Tag extends Model
{
    protected $connection = 'mysql';
    protected $table = 'tags';
    protected $primaryKey = 'id_tag';

    public const TAG_ALL = 'all';

    protected $hidden = [
    ];

    protected $visible = [
        'colla_id',
        'id_tag',
        'name',
        'type'
    ];

    #[ApiProperty(identifier: true)]
    private int $id_tag;
   
    // RELATIONS

    public function colla(): BelongsTo
    {
        return $this->belongsTo(Colla::class, 'colla_id', 'id_colla');
    }

    public function casteller(): ?BelongsToMany
    {
        return $this->belongsToMany(Casteller::class, 'casteller_tag', 'tag_id', 'casteller_id')
            ->where('castellers.colla_id', $this->getCollaId());
    }

    public function event(): ?BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_tag', 'event_id', 'tag_id');
    }

    // FUNCTIONS 

     public function getId(): int
     {
         return $this->getAttribute('id_tag');
     }
 
     public function getIdExternal(): ?int
     {
         return $this->getAttribute('id_tag_external');
     }
 
     public function getCollaId(): ?int
     {
         return $this->getAttribute('colla_id');
     }
 
     public function getColla(): ?Colla
     {
         return $this->getAttribute('colla');
     }
 
     public function getName(): string
     {
         return $this->getAttribute('name');
     }
 
     public function getValue(): string
     {
         return $this->getAttribute('value');
     }
 
     public function getGroup(): string
     {
         return $this->getAttribute('group');
     }
 
     public function getType(): string
     {
         return $this->getAttribute('type');
     }
}
