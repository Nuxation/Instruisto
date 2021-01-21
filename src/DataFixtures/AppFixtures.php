<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use App\Entity\Matiere;
use App\Entity\Annonce;

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

        // Creation des matieres
        $matiersNom = [
            "Sport", "Mathematiques", "Literature", "Physique/Chimie",
            "informatique", "Langues", "Science de la Terre", "Philosophie"
        ];
        $matieres = [];
        for ($i = 0; $i < count($matiersNom); $i++) {
            $matieres[$i] = new Matiere();
            $matieres[$i]->setNom($matiersNom[$i]);
            $manager->persist($matieres[$i]);
        }

        //creation des niveaux
        $niveauxNom = [
            "Primaire", "Collège", "Lycée", "Université", "Adulte"
        ];
        $niveaux = [];
        for ($i = 0; $i < count($niveauxNom); $i++) {
            $niveaux[$i] = new Matiere();
            $niveaux[$i]->setNom($niveauxNom[$i]);
            $manager->persist($niveaux[$i]);
        }

        // Creation des annonces
        $lieux = ["Salon A", "Salon B", "Salon C", "Salon D"];

        $anMatiere = $faker->randomElement($matieres);
        $anNiveau = $faker->randomElement($niveaux);
        $annonce = new Annonce();
        /*$annonce->setTitre("recherche un enseignant de " + $anMatiere->getNom())
            ->setDescription($faker->sentence($nbWords = 15, $variableNbWords = true))
            ->setPrix($faker->numberBetween(5, 150))
            ->setDureeEnMin($faker->numberBetween(10, 180))
            ->setLieux($lieux[array_rand($lieux)])
            ->setMatiere($anMatiere)
            ->setNiveau($anNiveau);

        //$manager->persist($annonce);*/

        $manager->flush();
    }
}
