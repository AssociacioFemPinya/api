<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Models\Notification;
use App\State\MobileNotificationsStateProvider;

#[ApiResource(
    shortName: 'MobileNotification',
    operations: [
        new Get(provider: MobileNotificationsStateProvider::class),
        new GetCollection(provider: MobileNotificationsStateProvider::class),
    ],
    paginationEnabled: false
)]

class MobileNotificationDto
{
    public const MODEL_CLASS = 'Notification';

    public function __construct(
        public ?int $id = null,
        public ?string $body = '',
        public ?string $title = '',
        public ?string $date = null,
        //TODO: Implement if Notification has been read
        public ?bool $isRead = true,
    ) {
    }

    public static function fromModel(Notification $notification): self
    {
        $dto = new self(
            id: $notification->getId(),
            title: $notification->getTitle(),
            body: (strlen($notification->render()) >= 100) ? substr($notification->render(), 0, 100).'...' : $notification->render(),
            date: $notification->getCreatedAt(),
            isRead: true,
        );
        return $dto;
    }

}
