<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Event;

final class EventsStateProvider extends AbstractStateProvider
{
    protected function collectionProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!is_null($this->casteller)) {
            $eventsFilter = Event::filter($this->casteller->getColla())
            ->upcoming()
            ->visible()
            ->withCastellerTags($this->casteller->tagsArray('id_tag'));

            // Apply filters based on params
            foreach ($this->parameters as $key => $value) {
                match ($key) {
                    'type' => $eventsFilter->withTypes([$value]),
                    default => null,
                };
            } 

            return $eventsFilter->eloquentBuilder()->get();

        } else {
            $events = Event::query();

            if (array_key_exists('type', $this->parameters)) {
                $events->where('type', (int)$this->parameters['type']['value']);
            }

            return $events->get();
        }

    }

}
