<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    //test
    const PASSWORD = "lolo";


    public function load(ObjectManager $manager)
    {


        $manager->flush();
    }

    private function createUser(string $email, array $roles)
    {
        $faker = Factory::create('FR-fr');
        $user = new User();
        $user->setEmail($email)
             ->setMotDePass(self::PASSWORD);

    }
}
