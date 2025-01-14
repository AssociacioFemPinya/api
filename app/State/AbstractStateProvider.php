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
use Illuminate\Support\Facades\Log;
use App\Models\ApiUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\Auth\Guard;

abstract class AbstractStateProvider implements ProviderInterface
{

    protected $casteller = null;

    public function __construct(
        protected ItemProvider $itemProvider,
        protected CollectionProvider $collectionProvider
    )
    {
        // we get the authenticatedUserId by Token and then we retrieve the actual ApiUser
        if (!is_null($identifiedUserId = Auth::guard('sanctum')->id())){
            $apiUser = ApiUser::find($identifiedUserId);
            $this->casteller = $apiUser->getCastellerActive();
        }
    }
    
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $data = [];

        if ($operation instanceof CollectionOperationInterface) {
            if($this->casteller){
                $operation = $this->setCastellerCollaParameter($operation);
                $context['operation'] = $operation;
            }
            $data = $this->collectionProvider->provide($operation,$uriVariables,$context);
            $data = $this->postCollectionProvider($data);
        } else {
            $data = $this->itemProvider->provide($operation,$uriVariables,$context);
            $data = $this->postItemProvider($data);
        }

        return $data;    
    }

    protected function postCollectionProvider($data) : mixed
    {
        return $data;
    }

    protected function postItemProvider($data) : mixed
    {
        return $data;
    }

    protected function setCastellerCollaParameter(Operation $operation) : Operation
    {

        $parameters = $operation->getParameters();
        $newParameters = [];

        foreach ($parameters ?? [] as $parameter) {
            if($parameter->getKey() === 'colla_id' && (!($values = $parameter->getValue()) 
                || $values instanceof ParameterNotFound)){
                    $parameter = $parameter->withExtraProperties(['_api_values'=>$this->casteller->getCollaId()]);
                }
            $newParameters[] = $parameter;

        }
        return $operation->withParameters($newParameters);

    }
}
