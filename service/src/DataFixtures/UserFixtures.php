<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Repository\UserGroupRepository;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $userGroupRepository;

    public function __construct(UserGroupRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $allUserGroups = $this->userGroupRepository->findAll();
        $countToCreate = 77;
        $faker = Factory::create();
        for ($i = 0; $i < $countToCreate; $i++)
        {
            $user = new User();
            $user->setName($faker->word);
            $user->setEmail($faker->unique()->email);
            $user->setUserGroup($faker->randomElement($allUserGroups));
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserGroupFixtures::class,
        ];
    }
}
