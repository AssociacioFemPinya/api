<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Colla;

trait FilterableTrait
{
    /**
     * @phpstan-ignore-next-line
     */
    public static function filter(Colla $colla)
    {
        if (!property_exists(static::class, 'filterClass')) {
            throw new \RuntimeException(sprintf('The class %s must define a static property $filterClass', static::class));
        }
        
        return new static::$filterClass($colla);
    }
}
