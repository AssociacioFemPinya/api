<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
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

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $id = $uriVariables['id'] ?? null;

        $model = $this->modelClass::filter($this->colla)->withId((int)$id)->eloquentBuilder()->firstOrFail();

        //$model = $this->modelClass::where('events.colla_id', $this->colla->getId())->findOrFail($id);

        return $this->modelClassDto::fromModel($model);
    }
}
