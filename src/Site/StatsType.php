<?php

namespace App\Site;

use App\GraphQL\Type\ObjectType;
use App\GraphQL\Type\Types;

final class StatsType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'StatsType',
            'fields' => [
                'orders' => Types::nonNull(Types::int()),
                'vehicles' => Types::nonNull(Types::int()),
                'customers' => Types::nonNull(new \GraphQL\Type\Definition\ObjectType([
                    'name' => 'StatsCustomersType',
                    'fields' => [
                        'persons' => Types::nonNull(Types::int()),
                        'organizations' => Types::nonNull(Types::int()),
                    ],
                ])),
                'reviews' => Types::nonNull(Types::int()),
            ],
        ];

        parent::__construct($config);
    }
}
