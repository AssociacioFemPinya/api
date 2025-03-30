<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Models\Casteller;
use App\State\MobileUserProfileStateProvider;

#[ApiResource(
    uriTemplate : '/mobile_user_profile',
    operations: [
        new Get(provider: MobileUserProfileStateProvider::class),
    ],
    paginationEnabled: false
)]

class MobileUserProfileDto
{

    public const MODEL_CLASS = '';

    public function __construct(
        public ?string $casteller_info = '',
    ) {
    }


    public static function fromModel(?Casteller $casteller): self
    {
        $dto = new self(
            casteller_info: json_encode($casteller)
        );
        return $dto;
    }

}
