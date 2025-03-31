<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\MobilePublicUrlStateProvider;

#[ApiResource(
    uriTemplate : '/public_display_url',
    operations: [
        new Get(provider: MobilePublicUrlStateProvider::class),
    ]
)]

class MobilePublicUrlDto
{
    public const MODEL_CLASS = '';

    public function __construct(
        public ?string $public_url = ''
    ) {
    }


    public static function fromModel(string $publicUrl): self
    {
        $dto = new self(
            public_url: $publicUrl
        );
        return $dto;
    }

}
