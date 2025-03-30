<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Models\Tag;

final class TagsStateProvider extends AbstractStateProvider
{
    protected function collectionProvider(Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        $tags = Tag::query();

        if (!is_null($this->casteller)) {
            $tags->where('colla_id', $this->colla->getId());
        }

        // Apply filters based on params
        foreach ($this->parameters as $key => $value) {
            match ($key) {
                'type' => $tags->where('type', (string)$value),
                default => null,
            };
        }

        return $tags->get();
    }
}
