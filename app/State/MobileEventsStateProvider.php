<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Event;
use App\Dto\MobileEventDto;
use Illuminate\Database\Eloquent\Collection;

class EventTag
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $isEnabled,
    ) {
    }
}

final class MobileEventsStateProvider extends MobileAbstractStateProvider
{
    protected function getModels(): Collection
    {

        $eventsFilter = $this->modelClass::filter($this->colla)
            ->upcoming()
            ->visible()
            ->withCastellerTags($this->casteller->tagsArray('id_tag'))
            ->showCastellerAttendance($this->casteller->getId());

        // Apply filters based on params
        foreach ($this->parameters as $key => $value) {
            match ($key) {
                'showAnswered' => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? $eventsFilter->showAnswered() : null,
                'showUndefined' => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? $eventsFilter->showUndefined() : null,
                //showWarning not implemented
                'startDate' => $eventsFilter->afterDate($value),
                'endDate' => $eventsFilter->beforeDate($value),
                'eventTypeFilters' => $eventsFilter->withTypes($value), // expect params in array like: eventTypeFilters\[\]\=2\&eventTypeFilters\[\]\=2
                default => null,
            };
        }

        return $eventsFilter->eloquentBuilder()->get();

    }

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $id = $uriVariables['id'] ?? null;
        if (is_null($id)) {
            abort(404, 'Event ID is required');
        }

        $event = Event::leftJoin('attendance', function ($join) {
            $join->on('events.id_event', '=', 'attendance.event_id')
                 ->where('attendance.casteller_id', $this->casteller->getId());
        })
            ->where('events.id_event', $id)
            ->where('events.colla_id', $this->colla->getId())
            ->select('events.*', 'attendance.companions', 'attendance.status', 'attendance.options')
            ->firstOrFail();

        $eventTags = [];
        $eventOptions = json_decode($event->options ?? "[]", true);
        foreach ($event->tags as $tag) {
            $eventTags[] = new EventTag($tag->id_tag, $tag->name, in_array($tag->id_tag, $eventOptions));
        }

        $mobileEvent = MobileEventDto::fromModel($event);
        $mobileEvent->tags = $eventTags;
        return $mobileEvent;
    }
}
