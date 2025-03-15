<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Event;
use App\Dto\MobileEventDto;

class EventTag {
    public function __construct(
        public int $id,
        public string $name,
        public bool $isEnabled,
    ) {}
}

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

        // Apply filters based on params
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

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $id = $uriVariables['id'] ?? null;
        if (is_null($id)) {
            abort(404, 'Event ID is required');
        }

        $event = Event::find($id)
            ->leftJoin('attendance', function($join) {
                $join->on('events.id_event', '=', 'attendance.event_id');
            })
            ->select('events.*', 'attendance.status', 'attendance.options')
            ->firstOrFail();

        if ($event->colla_id !== $this->casteller->getColla()->getId()) {
            abort(404, 'Event not found');
        }

        $eventTags = [];
        $eventOptions = json_decode($event->options, true);
        foreach ($event->tags as $tag){
            $eventTags[] = new EventTag($tag->id_tag, $tag->name, in_array($tag->id_tag, $eventOptions));
        }

        $mobileEvent = MobileEventDto::fromModel($event);
        $mobileEvent->tags = $eventTags;
        return $mobileEvent;
    }
}
