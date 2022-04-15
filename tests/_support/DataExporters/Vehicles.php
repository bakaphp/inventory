<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Support\DataExporters;

use Faker\Factory;
use Generator;
use Kanvas\Inventory\Contracts\ExportableInterface;
use Kanvas\Inventory\Enums\State;
use Phalcon\Utils\Slug;

class Vehicles implements ExportableInterface
{
    public function getAllEntities() : Generator
    {
        $faker = Factory::create();

        foreach (range(1, 10) as $index) {
            yield [
                'name' => $faker->name,
                'description' => $faker->realText(50),
                'handler' => $faker->slug,
                'is_published' => 1,
                'position' => rand(0, 10),
                'is_default' => State::DEFAULT,
                'sku' => Slug::generate($faker->name),
                'price' => $faker->randomDigit,
                'categories' => [
                    'name' => $faker->name,
                ],
                'images' => [
                    'image_url' => $faker->name,
                ],
                'product_attributes' => [
                    [
                        'name' => $faker->name,
                        'value' => $faker->name,
                    ],
                ],
                'variants_attributes' => [
                    [
                        'name' => $faker->name,
                        'value' => $faker->name,
                    ],
                    [
                        'name' => $faker->name,
                        'value' => $faker->name,
                    ],
                ]
            ];
        }
    }
}
