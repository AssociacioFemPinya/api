<?php

declare(strict_types=1);

namespace App\State;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

final class MobileRondesStateProvider extends MobileAbstractStateProvider
{
    protected function getModels(): Collection
    {
        $nextEvent = Event::filter($this->colla)->liveOrUpcoming()->visible()->eloquentBuilder()
        ->orderBy('start_date', 'asc')
        ->first();

        return $nextEvent->rondes()->get()->each(function ($ronda) use ($nextEvent) {
            $ronda->eventName = $nextEvent->title;
        });
    }
}
