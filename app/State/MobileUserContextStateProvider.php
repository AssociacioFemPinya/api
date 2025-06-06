<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;

final class MobileUserContextStateProvider extends MobileAbstractStateProvider
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $this->modelClassDto = $operation->getClass();

        if ($operation instanceof CollectionOperationInterface) {

            return new $this->modelClassDto();

        } else {

            $data = $this->itemProvider($operation, $uriVariables, $context);

        }

        return $data;
    }

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return $this->modelClassDto::fromModel([
            $this->casteller->getId(),
            $this->casteller->getDisplayName(),
            $this->apiUser->castellers()->toArray(),
            $this->colla->config->getBoardsEnabled()
        ]);

    }

}
