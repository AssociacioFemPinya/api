<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Support\Facades\Log;

final class MobilePublicUrlStateProvider extends MobileAbstractStateProvider
{

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        
        $this->modelClassDto = $operation->getClass();

        $publicUrl = $this->colla->config->getPublicDisplayUrl($this->casteller->getId());
        Log::info($publicUrl);

        return $this->modelClassDto::fromModel($publicUrl);
    }

}
