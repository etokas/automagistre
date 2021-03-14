<?php

namespace App\GraphQL\Type;

use App\GraphQL\Type\Definition\ConnectionType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;

abstract class ObjectType extends \GraphQL\Type\Definition\ObjectType
{
    private static array $instances;

    private static function instance(): static
    {
        return self::$instances[static::class] ??= new static([]);
    }

    public static function nullable(): static
    {
        return self::instance();
    }

    public static function notNull(): NonNull
    {
        return parent::nonNull(self::instance());
    }

    public static function list(): ListOfType
    {
        return parent::listOf(self::instance());
    }

    public static function connection(): ConnectionType
    {
        return self::$instances[static::class.'Connection'] ??= new ConnectionType(self::instance());
    }
}
