<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Laravel\Eloquent\State\CollectionProvider;
use ApiPlatform\Laravel\Eloquent\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\ApiUser;

abstract class AbstractStateProvider implements ProviderInterface
{
    protected $casteller = null;
    protected $colla = null;
    protected $parameters = [];
    protected $filter;

    public function __construct(
        protected ItemProvider $itemProvider,
        protected CollectionProvider $collectionProvider
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
        $this->parameters = $this->parseParameters($context);

        if ($operation instanceof CollectionOperationInterface) {
            extract($this->preCollectionProvider($operation, $uriVariables, $context));
            $data = $this->collectionProvider($operation, $uriVariables, $context);
            $data = $this->postCollectionProvider($data);
        } else {

            if (!isset($uriVariables['id'])) {
                abort(404, 'ID is required');
            }

            extract($this->preItemProvider($operation, $uriVariables, $context));
            $data = $this->itemProvider($operation, $uriVariables, $context);
            $data = $this->postItemProvider($data);
        }

        return $data;
    }

    protected function collectionProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return $this->collectionProvider->provide($operation, $uriVariables, $context);
    }

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return $this->itemProvider->provide($operation, $uriVariables, $context);
    }

    protected function preCollectionProvider(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        return [
            'operation'     => $operation,
            'uriVariables'  => $uriVariables,
            'context'       => $context
        ];
    }

    protected function postCollectionProvider($data): mixed
    {
        return $data;
    }

    protected function preItemProvider(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        return [
            'operation'     => $operation,
            'uriVariables'  => $uriVariables,
            'context'       => $context
        ];
    }

    protected function postItemProvider($data): mixed
    {
        return $data;
    }

    private function parseParameters(array $context): array
    {
        $parameters = [];
        $filters = $context['filters'] ?? [];

        foreach ($filters as $key => $value) {
            $parameters[$key] = $value;
        }

        return $parameters;
    }

    private function setUserInfo(): void
    {
        // we get the authenticatedUserId by Token and then we retrieve the actual ApiUser
        if (!is_null($identifiedUserId = Auth::guard('sanctum')->id())) {
            // Cache key could be a combination of user ID to make it unique per user
            $cacheKey = "casteller_active_{$identifiedUserId}";
            $collaCacheKey = "colla_active_{$identifiedUserId}";

            // Try to retrieve from the cache first
            $this->casteller = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($identifiedUserId) {
                // If not found in cache, retrieve from DB
                $apiUser = ApiUser::find($identifiedUserId);
                return $apiUser ? $apiUser->getCastellerActive() : null;
            });
            $this->colla = Cache::remember($collaCacheKey, now()->addMinutes(10), function () use ($identifiedUserId) {
                // If not found in cache, retrieve from DB
                return $this->casteller->getColla();
            });
        }
    }
}
