<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fidry\AliceDataFixtures\Loader\PurgerLoader;

class AppFixtures extends Fixture
{
    public function __construct(PurgerLoader $loader)
    {
        $this->loader = $loader;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loader->load([__DIR__ . '/../../fixtures/data.yaml']);
    }
}
