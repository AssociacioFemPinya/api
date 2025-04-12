<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Models\Ronda;
use App\State\MobileRondesStateProvider;

#[ApiResource(
    shortName: 'MobileRonda',
    operations: [
        new Get(provider: MobileRondesStateProvider::class),
        new GetCollection(provider: MobileRondesStateProvider::class),
    ],
    paginationEnabled: false
)]

class MobileRondaDto
{
    public const MODEL_CLASS = 'Ronda';

    public function __construct(
        public ?int $id = null,
        public ?string $publicUrl = '',
        public ?int $ronda = null,
        public ?string $name = '',
    ) {
    }

    public static function fromModel(Ronda $ronda): self
    {
        $dto = new self(
            id: $ronda->getId(),
            publicUrl: $ronda->boardEvent->getPublicUrl(),
            //publicUrl: "https://app.fempinya.cat/public/display/AireNou/WWN5Wk9aTnl4Q3FHUTE5bklsTkdCOFEvQ1BLWVB4M1BveVpRYlNJbkE1bDZ2SVBNTUlIbzI3S1RXUGRlVlBsUQ==",
            ronda: $ronda->ronda,
            name: $ronda->boardEvent->getDisplayName()
        );
        return $dto;
    }
}
