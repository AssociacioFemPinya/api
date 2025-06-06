<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Models\CollaConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ApiResource(
    shortName: 'Colla',
    operations: [
        new Get(
        ),
        new GetCollection(
        ),
    ],
)]
class Colla extends Model
{
    protected $connection = 'mysql';

    protected $table = 'colles';

    #[ApiProperty(identifier: true)]
    private int $id_colla;

    //Properties
    public function getId(): int
    {
        return $this->getAttribute('id_colla');
    }

    public function getConfig(): ?CollaConfig
    {
        return $this->getAttribute('config');
    }

    // RELATIONS

    public function events(): ?HasMany
    {
        return $this->hasMany(Event::class, 'colla_id', 'id_colla');
    }

    public function castellers(): ?HasMany
    {
        return $this->hasMany(Casteller::class, 'colla_id', 'id_colla');
    }

    public function getShortName(): ?string
    {
        return $this->getAttribute('shortname');
    }

    public function config(): ?HasOne
    {
        return $this->hasOne(CollaConfig::class, 'colla_id', 'id_colla');
    }

}
