<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;

final class MobileUserProfileStateProvider extends MobileAbstractStateProvider
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $this->modelClassDto = $operation->getClass();

        return $this->modelClassDto::fromModel($this->casteller);
    }

}
