<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Event;
use App\Dto\MobileEventDto;
use App\Dto\MobileNotificationDto;
use App\Enums\NotificationTypeEnum;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

final class MobileNotificationsStateProvider extends MobileAbstractStateProvider
{
    protected function getModels() : Collection
    {

        $notifications = [];
        $column_order = 'created_at';
        $dir = 'DESC';

        $notificationsAll = $this->modelClass::filter($this->colla)
            ->withTypes([NotificationTypeEnum::MESSAGE, NotificationTypeEnum::SCHEDULED_MESSAGE])
            ->visible()
            ->eloquentBuilder()
            ->orderBy($column_order, $dir)
            ->get();

        return $notificationsAll;

    }

}
