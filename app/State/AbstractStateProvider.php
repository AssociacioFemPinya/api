<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Laravel\Eloquent\State\CollectionProvider;
use ApiPlatform\Laravel\Eloquent\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ParameterNotFound;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\ApiUser;

abstract class AbstractStateProvider implements ProviderInterface
{
    protected $casteller = null;
    protected $parameters = [];

    public function __construct(
        protected ItemProvider $itemProvider,
        protected CollectionProvider $collectionProvider
    ) {
        // we get the authenticatedUserId by Token and then we retrieve the actual ApiUser
        if (!is_null($identifiedUserId = Auth::guard('sanctum')->id())) {
            $apiUser = ApiUser::find($identifiedUserId);
            $this->casteller = $apiUser->getCastellerActive();
        }
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $this->parameters = $this->parseParameters($operation);

        if ($operation instanceof CollectionOperationInterface) {
            extract($this->preCollectionProvider($operation, $uriVariables, $context));
            $data = $this->collectionProvider($operation, $uriVariables, $context);
            $data = $this->postCollectionProvider($data);
        } else {
            extract($this->preItemProvider($operation, $uriVariables, $context));
            $data = $this->itemProvider($operation, $uriVariables, $context);
            $data = $this->postItemProvider($data);
        }

        return $data;
    }

    protected function collectionProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return  $this->collectionProvider->provide($operation, $uriVariables, $context);
    }

    protected function itemProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return  $this->itemProvider->provide($operation, $uriVariables, $context);
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

    private function parseParameters(Operation $operation): array
    {
        $parameters = [];

        $parametersInput = $operation->getParameters();

        foreach ($parametersInput ?? [] as $parameter) {

            if (!is_null($values = $parameter->getValue()) && !$values instanceof ParameterNotFound && ! empty($values) && $values !== '') {
                $parameters[$parameter->getKey()] = [
                    'value'     => $parameter->getValue(),
                    'filter'    => $parameter->getFilter(),
                ];
            }
        }

        return $parameters;
    }

}
