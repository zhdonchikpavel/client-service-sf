<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\UserGroup;
use Faker\Factory;

class UserGroupFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $countToCreate = 5;
        $faker = Factory::create();
        for ($i = 0; $i < $countToCreate; $i++)
        {
            $category = new UserGroup();
            $category->setName($faker->unique()->company);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
