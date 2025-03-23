<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Enums\TypeTags;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ApiResource(
    shortName: 'Casteller',
    operations: [
        new Get(
        ),
        new GetCollection(
        ),
    ],
)]
class Casteller extends Model
{
    protected $connection = 'mysql';
    protected $table = 'castellers';
    protected $primaryKey = 'id_casteller';

    #[ApiProperty(identifier: true)]
    private int $id_casteller;

    // Relations

    public function apiUsers(): ?BelongsToMany
    {
         return $this->belongsToMany(ApiUser::class, 'casteller_api_user', 'casteller_id', 'api_user_id');
    }

    public function castellerConfig(): HasOne
    {
        return $this->hasOne(CastellerConfig::class, 'casteller_id', 'id_casteller');
    }

    public function colla(): BelongsTo
    {
        return $this->belongsTo(Colla::class, 'colla_id', 'id_colla');
    }

    public function tags(): ?BelongsToMany
    {
        return $this
            ->belongsToMany(Tag::class, 'casteller_tag', 'casteller_id', 'tag_id');
    }

    // Functions
    public function tagsArray(string $return_type = 'name'): array
    {
        return $this->getTags()->pluck($return_type)->toArray();
    }

    public function getTags(): Collection
    {
        return $this->tags->where('type', TypeTags::Castellers()->value());
    }

    public function getId(): int
    {
        return $this->getAttribute('id_casteller');
    }

    public function getCollaId(): int
    {
        return $this->getAttribute('colla_id');
    }

    public function getColla(): Colla
    {
        return $this->getAttribute('colla');
    }

    public function getDisplayName(string $config = ' [alias]  [name] [last_name]'): string
    {
        return $this->getAlias();
    }
    
    public function getAlias(): string
    {
        return $this->getAttribute('alias');
    }

}
