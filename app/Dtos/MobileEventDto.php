<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection; 
use App\Models\Event;
use App\State\MobileEventsStateProvider;

#[ApiResource(
    shortName: 'MobileEvent',
    operations: [
        new Get(provider: MobileEventsStateProvider::class),
        new GetCollection(provider: MobileEventsStateProvider::class),
    ],
    paginationEnabled: false
)]
class MobileEventDto
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $address = '',
        public ?string $status = 'undefined', // statuses(enum): accepted, declined, unknown, undefined, warning
        public ?string $type = '', // type(enum): training, performance, activity
        public ?string $description = '',
        public ?int $companions = null,
        public ?string $comment = '',
    ) {}

    public static function fromModel(Event $event): MobileEventDto {
        $statusMap = [
            null => 'undefined',
            1 => 'accepted',
            2 => 'declined',
            3 => 'unknown',
        ];

        $typeMap = [
            1 => 'training',
            2 => 'performance',
            3 => 'activity',
        ];

        return new self(
            id: $event->id_event,
            title: $event->name,
            startDate: $event->start_date,
            endDate: $event->close_date,
            address: $event->address,
            status: $statusMap[$event->status] ?? 'unknown',
            type: $typeMap[$event->type] ?? '',
            description: $event->comments, // TODO: this should be a description
            companions: $event->companions,
        );
    }
}
