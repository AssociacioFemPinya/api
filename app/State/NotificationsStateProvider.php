<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Notification;

final class NotificationsStateProvider extends AbstractStateProvider
{
    protected function collectionProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!is_null($this->casteller)) {
            $notificationsFilter = Notification::filter($this->colla);

            if (array_key_exists('type', $this->parameters)) {
                $notificationsFilter->withType($this->parameters['type']['value']);
            }

            return $notificationsFilter->eloquentBuilder()->get();

        } else {

            $notifications = Notification::query();

            if (array_key_exists('type', $this->parameters)) {
                $notifications->where('type', (int)$this->parameters['type']['value']);
            }

            return $notifications->get();
        }

    }

}
