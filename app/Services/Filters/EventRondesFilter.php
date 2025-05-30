<?php

declare(strict_types=1);

namespace App\Services\Filters;

use App\Models\Colla;
use App\Models\Event;
use App\Models\Ronda;
use App\Traits\DatatablesFilterTrait;
use Illuminate\Database\Eloquent\Builder;

class EventRondesFilter extends BaseFilter
{
    use DatatablesFilterTrait;
    private Builder $eloquentBuilder;

    public function __construct(Colla $colla)
    {
        parent::__construct($this->eloquentBuilder = Ronda::query()->leftJoin(
            'events',
            'events.id_event',
            '=',
            'rondes.event_id'
        )
            ->where('events.colla_id', $colla->getId())
            ->select('rondes.*'));
    }

    public function inEvent(Event $event): self
    {
        $this->eloquentBuilder
            ->where('events.id_event', $event->getId());

        return $this;
    }

    public function withId(int $id): self
    {
        $this->eloquentBuilder
            ->where('rondes.id_ronda', $id);

        return $this;
    }
}
