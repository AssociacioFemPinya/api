<?php
/**
 * 
 * Unused version trying to use Api-Platform Parameters
 * 
 */
declare(strict_types=1);

namespace App\State;

use ApiPlatform\Laravel\Eloquent\State\CollectionProvider;
use ApiPlatform\Laravel\Eloquent\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ParameterNotFound;
use ApiPlatform\State\ProviderInterface;
use App\Dtos\TagDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ApiUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Event;
use App\Models\Tag;

abstract class --AbstractStateProvider implements ProviderInterface
{

    protected $casteller = null;
    protected $model = null;

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
        // we get the actual Model when using Dtos so we can use default Api-Platform Filtering
        $resourceModel = $operation->getClass();
        $this->model = (defined($resourceModel.'::PARENT_MODEL')) ? TagDto::PARENT_MODEL : $resourceModel ;
        $operation = $operation->withClass('\App\Models\Tag');
        $context['operation'] = $operation;

        $data = [];

        if ($operation instanceof CollectionOperationInterface) {
            if($this->casteller){
                $operation = $this->setParameter($operation, 'colla_id', $this->casteller->getCollaId());
                $context['operation'] = $operation;
            }

            extract($this->preCollectionProvider($operation,$uriVariables,$context));
            $data = $this->collectionProvider->provide($operation,$uriVariables,$context);
            $data = $this->postCollectionProvider($data);
        } else {
            extract($this->preItemProvider($operation,$uriVariables,$context));
            $data = $this->itemProvider->provide($operation,$uriVariables,$context);
            $data = $this->postItemProvider($data);
        }

        return $data;    
    }

    protected function preCollectionProvider(Operation $operation, array $uriVariables = [], array $context = []) : array
    {
        return [
            'operation'     => $operation,
            'uriVariables'  => $uriVariables,
            'context'       => $context
        ];
    }

    protected function postCollectionProvider($data) : mixed
    {
        return $data;
    }

    protected function preItemProvider(Operation $operation, array $uriVariables = [], array $context = []) : array
    {
        return [
            'operation'     => $operation,
            'uriVariables'  => $uriVariables,
            'context'       => $context
        ];    
    }    

    protected function postItemProvider($data) : mixed
    {
        return $data;
    }

    protected function setParameter(Operation $operation, string $parameterName, mixed $parameterValue) : Operation
    {

        $parameters = $operation->getParameters();
        $newParameters = [];

        foreach ($parameters ?? [] as $parameter) {
            $values = $parameter->getValue();
            if($parameter->getKey() === $parameterName
                // if no parameter nor parameter value has set, we override the paremeter. Otherwise we respect it
                && (is_null($values = $parameter->getValue()) || $values instanceof ParameterNotFound || empty($values) || $values === '')){
                    $parameter = $parameter->withExtraProperties(['_api_values' => $parameterValue]);
            }
            $newParameters[] = $parameter;
        }
        return $operation->withParameters($newParameters);

    }
}
