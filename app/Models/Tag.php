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

#[ApiResource(
    shortName: 'Tag',
    normalizationContext: ['jsonld_embed_code' => true],
    operations: [
        new Get(
        ),
        new GetCollection(
        ),
    ],
)]
class Tag extends Model
{
    protected $table = 'tags'; 

    #[ApiProperty(identifier: true)]
    private int $id_tag;

    public function event(): ?BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_tag', 'event_id', 'tag_id');
    }

}