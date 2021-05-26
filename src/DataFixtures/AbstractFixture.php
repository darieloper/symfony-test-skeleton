<?php

namespace App\DataFixtures;

use Bezhanov\Faker\ProviderCollectionHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;

abstract class AbstractFixture extends Fixture {
    /**
     * @var Generator
     */
    protected $faker;

    public function __construct() {
        $this->faker = Factory::create();
        ProviderCollectionHelper::addAllProvidersTo($this->faker);
    }
}