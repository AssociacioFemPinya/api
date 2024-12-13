<?php

declare(strict_types=1);

namespace App\State;

use App\Dtos\EventDto;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use ApiPlatform\Laravel\Eloquent\State\CollectionProvider;
use ApiPlatform\Laravel\Eloquent\State\ItemProvider;
use Illuminate\Support\Facades\Auth;


final class EventStateProvider implements ProviderInterface
{

    public function __construct(
        private CollectionProvider $collectionProvider,
        private ItemProvider $itemProvider
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
    
        if ($operation instanceof CollectionOperationInterface) {

            $paginator = $this->collectionProvider->provide($operation, $uriVariables, $context);

            $events = [];
            
            foreach ($paginator as $pagine){

                $events[] = $pagine;
                // do stuff
            }

            // do stuff

            return $events;

            
        }else{

            $event = $this->itemProvider->provide($operation, $uriVariables, $context);

            if (!$event) {
                return null;
            }

            // do stuff

            return $event;

        }

    }


}
