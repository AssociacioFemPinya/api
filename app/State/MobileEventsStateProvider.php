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
        if (is_null($this->casteller)) {
            return [];
        }

        $eventsFilter = Event::filter($this->casteller->getColla())
            ->upcoming()
            ->visible()
            ->withCastellerTags($this->casteller->tagsArray('id_tag'))
            ->showCastellerAttendance($this->casteller->getId());

        // Apply params
        foreach ($this->parameters as $key => $value) {
            match ($key) {
                'showAnswered' => $eventsFilter->showAnswered(),
                'showUnknown' => $eventsFilter->showUnknown(),
                'startDate' => $eventsFilter->afterDate($value),
                'endDate' => $eventsFilter->beforeDate($value),
                'eventTypeFilters' => $eventsFilter->withTypes($value), // expect params in array like: eventTypeFilters\[\]\=2\&eventTypeFilters\[\]\=2
                default => null,
            };
        }

        $events = $eventsFilter->eloquentBuilder()->get();
        return $events->map(fn($event): MobileEventDto => MobileEventDto::fromModel(event: $event));
    }
}
