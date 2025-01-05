<?php

declare(strict_types=1);

namespace App\State;

use App\Models\GlobalPicklist;
use App\Models\GlobalPicklistValue;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Laravel\Eloquent\State\PersistProcessor;
use ApiPlatform\Laravel\Eloquent\State\RemoveProcessor;
use Illuminate\Support\Facades\Log;


abstract class AbstractStateProcessor implements ProcessorInterface
{

    public function __construct(
        protected PersistProcessor $persistProcessor,
        protected RemoveProcessor $removeProcessor
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        if ($operation instanceof DeleteOperationInterface) {
            
            $data = $this->preRemoveProcessor($data);
            $data = $this->removeProcessor->process($data, $operation, $uriVariables, $context);
            $data = $this->postRemoveProcessor($data);

        }else{

            $data = $this->preProcessProcessor($data);
            $data = $this->persistProcessor->process($data, $operation, $uriVariables, $context);
            $data = $this->postProcessProcessor($data);
            }

        return $data;

    }

    protected function preRemoveProcessor($data) : mixed
    {
        return $data;
    }

    protected function postRemoveProcessor($data) : mixed
    {
        return $data;
    }

    protected function preProcessProcessor($data) : mixed
    {
        return $data;
    }

    protected function postProcessProcessor($data) : mixed
    {
        return $data;
    }
}

