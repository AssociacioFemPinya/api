<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Event;
use App\Dto\MobileEventDto;


final class MobileEventsStateProvider extends AbstractStateProvider
{
    protected function collectionProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!is_null($this->casteller)) {
            $eventsFilter = Event::filter($this->casteller->getColla())
                ->upcoming()
                ->visible()
                ->withCastellerTags($this->casteller->tagsArray('id_tag'))
                ->showCastellerAttendance($this->casteller->getId());

        //     // if (array_key_exists('type', $this->parameters)) {
        //     //     $eventsFilter->withType($this->parameters['type']['value']);
        //     // }

            $events = $eventsFilter->eloquentBuilder()->get();
            return $events->map(fn($event): MobileEventDto => MobileEventDto::fromModel(event: $event));
        } else {
            return [];
        }
    }
}
