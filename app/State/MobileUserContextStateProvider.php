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

final class MobileUserContextStateProvider implements ProviderInterface
{

    protected $apiUser =  null;
    protected $casteller = null;
    protected $colla = null;
    protected $modelClassDto = null;


    public function __construct(
    ) {
        try {
            $this->setUserInfo();
        } catch (\Exception $e) {
            Log::debug('Error getting the authenticated user: ' . $e->getMessage());
        }

        if (is_null($this->casteller)) {
            abort(404, 'Casteller not found');
        }

    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $this->modelClassDto = $operation->getClass();

        if ($operation instanceof CollectionOperationInterface) {

            return new $this->modelClassDto();

        } else {

            $data = $this->itemProvider($operation, $uriVariables, $context);

        }

        return $data;
    }

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return $this->modelClassDto::fromModel([
            $this->casteller->getId(),
            $this->casteller->getDisplayName(),
            $this->apiUser->castellers()->toArray(),
            $this->colla->config->getBoardsEnabled()
        ]);

    }

    private function setUserInfo(): void
    {
        // we get the authenticatedUserId by Token and then we retrieve the actual ApiUser
        if (!is_null($identifiedUserId = Auth::guard('sanctum')->id())) {
            // Cache key could be a combination of user ID to make it unique per user
            $cacheKey = "casteller_active_{$identifiedUserId}";
            $collaCacheKey = "colla_active_{$identifiedUserId}";

            $this->apiUser = ApiUser::find($identifiedUserId);

            // Try to retrieve from the cache first
            $this->casteller = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($identifiedUserId) {
                // If not found in cache, retrieve from DB
                return $this->apiUser ? $this->apiUser->getCastellerActive() : null;
            });
            $this->colla = Cache::remember($collaCacheKey, now()->addMinutes(10), function () use ($identifiedUserId) {
                // If not found in cache, retrieve from DB
                return $this->casteller->getColla();
            });
        }
    }

}
