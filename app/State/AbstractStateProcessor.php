<?php

declare(strict_types=1);

namespace App\State;

use App\Models\ApiUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Laravel\Eloquent\State\PersistProcessor;
use ApiPlatform\Laravel\Eloquent\State\RemoveProcessor;

abstract class AbstractStateProcessor implements ProcessorInterface
{
    protected $casteller = null;
    
    public function __construct(
        protected PersistProcessor $persistProcessor,
        protected RemoveProcessor $removeProcessor
    ) {
        try {
            Log::info('AbstractStateProcessor');
            // we get the authenticatedUserId by Token and then we retrieve the actual ApiUser
            if (!is_null($identifiedUserId = Auth::guard('sanctum')->id())) {
                $apiUser = ApiUser::find($identifiedUserId);
                $this->casteller = $apiUser->getCastellerActive();
            }
        } catch (\Exception $e) {
            Log::debug('Error getting the authenticated user: ' . $e->getMessage());
        }
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof DeleteOperationInterface) {

            $data = $this->preRemoveProcessor($data);
            //$data = $this->removeProcessor->process($data, $operation, $uriVariables, $context);
            $data = $this->postRemoveProcessor($data);

        } else {

            $data = $this->preProcessProcessor($data, $uriVariables);
            //$data = $this->persistProcessor->process($data, $operation, $uriVariables, $context);
            $data = $this->postProcessProcessor($data, $uriVariables);
        }

        return $data;

    }

    protected function preRemoveProcessor(mixed $data): mixed
    {
        return $data;
    }

    protected function postRemoveProcessor(mixed $data): mixed
    {
        return $data;
    }

    protected function preProcessProcessor(mixed $data, array $uriVariables = []): mixed
    {
        return $data;
    }

    protected function postProcessProcessor(mixed $data, array $uriVariables = []): mixed
    {
        return $data;
    }
}
