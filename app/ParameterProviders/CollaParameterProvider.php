<?php

namespace App\ParameterProvers;

use ApiPlatform\State\ParameterProviderInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Parameter;
use Illuminate\Support\Facades\Log;

class CollaParameterProvider implements ParameterProviderInterface {

    public function provide(Parameter $parameter, array $parameters = [], array $context = []): ?Operation
    {
        Log::info('CollaParameterProvider');
        $parameters = $context['operation']->getParameters();
        foreach ($parameters ?? [] as $parameter) {
            Log::info($parameter);
           /* if (!($values = $parameter->getValue()) || $values instanceof ParameterNotFound) {
                continue;
            }*/
        }
        return $context['operation'];

    }
}

