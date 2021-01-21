<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $diplome = ["Diplome A", "Diplome B", "Diplome C", "Diplome D"];
        $faker = Factory::create();
        for($i = 0; $i < 5;$i++) {
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            //$user->setRoles(['ROLE_USER']);
            $user->setEmail($faker->firstName.'-'.$faker->lastName.'@etud.fr');
            $user->setTelephone($faker->phoneNumber());
            $user->setGenre($faker->randomElement(["Homme", "Femme"]));
            //$encrypted = $this->passwordEncoder->encodePassword('test');
            $user->setMotDePass("test");
            $user->setDateDeNaissance($faker->dateTime());
            $user->setPresentation($faker->paragraph);
            $user->setEtudeEtDiplome($diplome[array_rand($diplome)]);
            $manager->persist($user);

            $manager->flush();

        }
        $admin = new User();
        $admin->setNom($faker->lastName);
        $admin->setPrenom($faker->firstName);
        //$admin->setRoles(['ROLE_ADMIN']);
        $admin->setEmail($faker->firstName.'-'.$faker->lastName.'@admin.fr');
        $admin->setTelephone($faker->phoneNumber());
        $admin->setGenre($faker->randomElement(["Homme", "Femme"]));
        //$encrypted = $this->passwordEncoder->encodePassword('admin');
        $admin->setMotDePass("admin");
        $manager->persist($admin);

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
