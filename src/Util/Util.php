<?php

namespace Lumie\WarehouseManagerApplication\Util;

class Util
{
    public static function isArrayType(string $type, array $array)
    {
        return array_reduce($array, function ($carry, $item) use ($type) {
            switch ($type) {
                case 'int':
                    return $carry && is_int($item);
                case 'string':
                    return $carry && is_string($item);
                case 'array':
                    return $carry && is_array($item);
                default:
                    return $carry && $item instanceof $type;
            }
        }, true);
    }


    public static function checkArrayType(string $class, array $array)
    {
        if (!static::isArrayType($class, $array)) {
            throw new \Exception("Invalid value type provided to array. Expected: $class");
        }
    }
}
