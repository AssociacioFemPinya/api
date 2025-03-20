<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;
use App\Models\Event;
use App\State\MobileEventsStateProvider;
use App\State\MobileEventsStateProcessor;
use Illuminate\Support\Facades\Log;


#[ApiResource(
    shortName: 'MobileEvent',
    operations: [
        new Get(provider: MobileEventsStateProvider::class),
        new GetCollection(provider: MobileEventsStateProvider::class),
        new Put(
            provider: MobileEventsStateProvider::class,
            processor: MobileEventsStateProcessor::class,
        ),
    ],
    paginationEnabled: false
)]

class MobileEventDto
{
    public function __construct(
        public ?int $id = null,
        public ?string $title = '',
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $address = '',
        public ?string $status = 'undefined', // statuses(enum): accepted, declined, unknown, undefined, warning
        public ?string $type = '', // type(enum): training, performance, activity
        public ?string $description = '',
        public ?int $companions = null,
        public ?array $tags = [],
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

        $dto = new self(
            id: $event->id_event,
            title: $event->name,
            startDate: $event->start_date,
            endDate: $event->close_date,
            address: $event->address,
            status: $statusMap[$event->status] ?? 'unknown',
            type: $typeMap[$event->type] ?? '',
            description: $event->comments, // TODO: the field in the DB should be description instead?
            companions: $event->companions,
            // tags is not generated for Collection, only for single item. It must be generated in a separate query
        );
        return $dto;
    }
}
