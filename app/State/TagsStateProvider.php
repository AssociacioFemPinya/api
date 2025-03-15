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
            $tags->where('colla_id', $this->casteller->getColla());
        }

        if (array_key_exists('type', $this->parameters)) {
            $tags->where('type', (string)$this->parameters['type']['value']);
        }

        return $tags->get();
    }
}
