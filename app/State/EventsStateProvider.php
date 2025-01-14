<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Support\Facades\Log;
use App\State\AbstractStateProvider;

final class EventsStateProvider extends AbstractStateProvider
{

    protected function preCollectionProvider(Operation $operation, array $uriVariables = [], array $context = []) : array
    {

        //Returning only visible Events
        $operation = $this->setParameter($operation, 'visibility', 1);
        $context['operation'] = $operation;

        return [
            'operation'     => $operation,
            'uriVariables'  => $uriVariables,
            'context'       => $context
        ];
    }

}
