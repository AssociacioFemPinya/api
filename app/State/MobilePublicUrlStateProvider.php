<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;

final class MobilePublicUrlStateProvider extends MobileAbstractStateProvider
{

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $publicUrl = $this->colla->config->getPublicDisplayUrl($this->casteller->getId());

        return $this->modelClassDto::fromModel($publicUrl);
    }

}
