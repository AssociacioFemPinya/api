<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Enums\NotificationTypeEnum;
use App\Models\ApiUser;

use function PHPSTORM_META\map;

final class MobileUserProfileStateProvider extends MobileAbstractStateProvider
{

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $this->modelClassDto = $operation->getClass();

        return $this->modelClassDto::fromModel($this->casteller);
    }

}
