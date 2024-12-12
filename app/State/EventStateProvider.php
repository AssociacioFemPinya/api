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
            
            Log::info(Auth::user());

            // do stuff

            return $paginator;

            
        }else{

            $event = $this->itemProvider->provide($operation, $uriVariables, $context);

            // do stuff

            if (!$event) {
                return null;
            }

            return $event;

        }

    }


}
