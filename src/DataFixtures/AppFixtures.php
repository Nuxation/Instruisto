<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use App\Entity\Matiere;

class AppFixtures extends Fixture
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
        $faker = Factory::create('fr_FR');

        //creation des Utilisateurs
        $diplome = ["Diplome A", "Diplome B", "Diplome C", "Diplome D"];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setEmail($faker->firstName . '-' . $faker->lastName . '@etud.fr')
                ->setTelephone($faker->phoneNumber())
                ->setGenre($faker->randomElement(["Homme", "Femme"]))
                //$encrypted = $this->passwordEncoder->encodePassword('test');
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'test'
                ))
                ->setDateDeNaissance(new \DateTime())
                ->setPresentation($faker->paragraph)
                ->setEtudeEtDiplome($diplome[array_rand($diplome)]);
            $manager->persist($user);
        }
        $admin = new User();
        $admin->setNom("haspadar")
            ->setPrenom("instruisto")
            ->setEmail('haspadar@admin.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setTelephone($faker->phoneNumber())
            ->setGenre("Homme")
            //$encrypted = $this->passwordEncoder->encodePassword('test');
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'test'
            ))
            ->setDateDeNaissance(new \DateTime())
            ->setPresentation($faker->paragraph)
            ->setEtudeEtDiplome($diplome[array_rand($diplome)]);
        $manager->persist($admin);

        $manager->flush();
    }
}
