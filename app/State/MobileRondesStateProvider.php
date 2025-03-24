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
        $nextEvent = Event::filter($this->colla)
        ->liveOrUpcoming()
        ->visible()
        ->with('rondes')
        ->with('colla')
        ->with('boardsEvent')
        ->eloquentBuilder()
        ->orderBy('start_date', 'asc')
        ->firstOrFail();

        return $nextEvent->rondes;

    }

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $id = $uriVariables['id'] ?? null;

        $model = $this->modelClass::filter($this->colla)
                ->withId((int)$id)
                ->with('boardEvent')
                ->with('boardEvent.board')
                ->with('event')
                ->with('event.colla')
                ->eloquentBuilder()
                ->firstOrFail();

        return $this->modelClassDto::fromModel($model);
    }
}
