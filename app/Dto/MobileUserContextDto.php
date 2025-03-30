<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\MobileUserContextStateProvider;

#[ApiResource(
    uriTemplate : '/mobile_user_context',
    operations: [
        new Get(provider: MobileUserContextStateProvider::class),
    ],
    paginationEnabled: false
)]

class MobileUserContextDto
{
    public const MODEL_CLASS = '';

    public function __construct(
        public ?int $casteller_active_id = null,
        public ?string $casteller_active_alias = '',
        public ?array $linked_castellers = [],
        public ?bool $boards_enabled = true,
    ) {
    }


    public static function fromModel(array $apiUserInfo): self
    {
        $dto = new self(
            casteller_active_id: $apiUserInfo[0],
            casteller_active_alias: $apiUserInfo[1],
            linked_castellers: $apiUserInfo[2],
            boards_enabled: $apiUserInfo[3]
        );
        return $dto;
    }

}
