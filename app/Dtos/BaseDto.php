<?php
namespace App\Dtos;
use Symfony\Component\Validator\Constraints as Assert;
abstract class BaseDto
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array  = [];
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties() as $key => $value) {
            $value->setAccessible(true);
            $array[$value->getName()] = $value->getValue($this);
        }
        return $array;
    }
}