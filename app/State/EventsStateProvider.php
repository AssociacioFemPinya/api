<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Event;
use App\State\AbstractStateProvider;

final class EventsStateProvider extends AbstractStateProvider
{

    protected function collectionProvider(Operation $operation, array $uriVariables = [], array $context = []) : mixed
    {
        if(!is_null($this->casteller)){
            $eventsFilter = Event::filter($this->casteller->getColla())
            ->upcoming()
            ->visible()
            ->withCastellerTags($this->casteller->tagsArray('id_tag'));

            if(array_key_exists('type',$this->parameters)) $eventsFilter->withType($this->parameters['type']['value']);

            return $eventsFilter->eloquentBuilder()->get();

        }else{
            $events = Event::query();

            if(array_key_exists('type',$this->parameters)) $events->where('type',(int)$this->parameters['type']['value']);

            return $events->get();
        }
 
    }

}
