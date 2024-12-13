<?php

declare(strict_types=1);

namespace App\State;

use App\Dtos\EventDto;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use Illuminate\Support\Facades\Log;
use App\Models\Event;

final class EventStateProviderDTO implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        if (!$operation instanceof CollectionOperationInterface) {
            $event = Event::find($uriVariables['id_event']);
            Log::info('EventStateProvider');
            if ($event) {
                $leadDto = $this->parseEventToEventDto($event);
                //Log::info(array($leadDto));
                return $leadDto;
            } else {
                return null;
            }
        } else {
            $events = Event::all();
            $EventDtos = [];
            foreach ($events as $event) {
                $EventDtos[] = $this->parseEventToEventDto($event);
            }
            //log::info(array($LeadDtos));
            return $EventDtos;
        }

    }

    private function parseEventToEventDto(Event $event): EventDto
    {
        $EventDto = new EventDto();
        $EventDto->id = $event->id_event;
        $EventDto->name = $event->name;
        return $EventDto;
    }
}
