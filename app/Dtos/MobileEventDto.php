<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection; 
use App\Models\Event;
use App\State\MobileEventsStateProvider;
use Illuminate\Support\Facades\Log;


#[ApiResource(
    shortName: 'MobileEvent',
    operations: [
        new Get(provider: MobileEventsStateProvider::class),
        new GetCollection(provider: MobileEventsStateProvider::class)
    ],
    paginationEnabled: false
)]

class MobileEventDto
{
    public function __construct(
        public int $id,
        public String $title,
        public ?String $startDate = null,
        public ?String $endDate = null,
        public ?String $address = '',
        public ?String $status = '', // statuses(enum): accepted, declined, unknown, undefined, warning
        public ?String $type = '', // type(enum):   training, performance, activity
        public ?String $description = '',
        public ?int $companions = null,
        //public List<TagModel>? $tags,
        public ?String $comment = '',
    ){}

    // TODO: This method shouldn't be fromModel as it returns things thatdoesn't belong to the model, like attendance
    public static function fromModel(Event $event): MobileEventDto {
        $statusMap = [
            null => 'undefined',
            1 => 'accepted',
            2 => 'declined',
            3 => 'unknown',
        ];
        $status = $statusMap[$event->status] ?? 'unknown';

        $typeMap = [
            1 => 'training',
            2 => 'performance',
            3 => 'activity',
        ];
        $type = $typeMap[$event->type];

        Log::info("MobileEventDto_event_comments", [$event]);

        return new self(
            id: $event->id_event,
            title: $event->name,
            startDate: $event->start_date,
            endDate: $event->close_date,
            address: $event->address,
            status: $status,
            type: $type,
            description: $event->comments, // TODO: this should be a description
            companions: $event->companions,
            //$event->tags,
            //comment: $event->comment,
        );
    }
}
