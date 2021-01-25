<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use App\Entity\StatusAnnonce;
use App\Entity\Presentiel;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Entity\Creneau;
use App\Entity\Annonce;
use App\Entity\Commentaire;

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
        $users = [];
        for ($i = 0; $i < 30; $i++) {
            $genre = $faker->randomElement(["Homme", "Femme"]);
            $prenom = $faker->firstName(($genre == "Homme") ? "male" : "female");
            $genreAvatar = (($genre == 'Homme') ? 'men' : 'women');
            $users[$i] = new User();
            $users[$i]->setNom($faker->lastName)
                ->setPrenom($prenom)
                ->setEmail($prenom . '-' . $faker->lastName . '@etud.fr')
                ->setTelephone($faker->phoneNumber())
                ->setGenre($genre)
                ->setAvatar("https://randomuser.me/api/portraits/{$genreAvatar}/{$faker->numberBetween(0, 99)}.jpg")
                ->setPassword($this->passwordEncoder->encodePassword(
                    $users[$i],
                    'test'
                ))
                ->setDateDeNaissance(new \DateTime())
                ->setPresentation($faker->paragraph)
                ->setEtudeEtDiplome($diplome[array_rand($diplome)]);
            $manager->persist($users[$i]);
        }
        $admin = new User();
        $admin->setNom("haspadar")
            ->setPrenom("instruisto")
            ->setEmail('haspadar@admin.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setTelephone($faker->phoneNumber())
            ->setGenre("Homme")
            ->setPassword($this->passwordEncoder->encodePassword(
                $admin,
                'test'
            ))
            ->setAvatar("/assets/images/icone/logoOriginal1080x1080.png")
            ->setDateDeNaissance(new \DateTime())
            ->setPresentation($faker->paragraph)
            ->setEtudeEtDiplome($diplome[array_rand($diplome)]);
        $manager->persist($admin);

        // Creation des matieres
        $matiersNom = [
            ["Sport", "/assets/images/icone/008-sport.png"],
            ["Mathematiques", "/assets/images/icone/003-design-tool.png"],
            ["Literature", "/assets/images/icone/001-book.png"],
            ["Physique/Chimie", "/assets/images/icone/002-atom.png"],
            ["informatique", "/assets/images/icone/004-computer.png"],
            ["Langues", "/assets/images/icone/006-translation.png"],
            ["Science de la Terre", "/assets/images/icone/005-botany.png"],
            ["Droit", "/assets/images/icone/007-auction.png"]
        ];
        $matieres = [];
        for ($i = 0; $i < count($matiersNom); $i++) {
            $matieres[$i] = new Matiere();
            $matieres[$i]
                ->setNom($matiersNom[$i][0])
                ->setImagePath($matiersNom[$i][1]);

            $manager->persist($matieres[$i]);
        }

        //creation des niveaux
        $niveauxNom = [
            "Primaire", "Collège", "Lycée", "Université", "Adulte"
        ];
        $niveaux = [];
        for ($i = 0; $i < count($niveauxNom); $i++) {
            $niveaux[$i] = new Niveau();
            $niveaux[$i]->setNom($niveauxNom[$i]);
            $manager->persist($niveaux[$i]);
        }

        //creation des status d'Annonce
        $statusNom = [
            "recherche_enseignant", "enseignant_validé", "cours_fini"
        ];
        $status = [];
        for ($i = 0; $i < count($statusNom); $i++) {
            $status[$i] = new StatusAnnonce();
            $status[$i]->setNom($statusNom[$i]);
            $manager->persist($status[$i]);
        }

        //Presentiel ou distanciel
        $PresentielNom = [
            "En Ligne", "En personne"
        ];
        $Presentiels = [];
        for ($i = 0; $i < count($PresentielNom); $i++) {
            $Presentiels[$i] = new Presentiel();
            $Presentiels[$i]->setNom($PresentielNom[$i]);
            $manager->persist($Presentiels[$i]);
        }


        // Creation des annonces
        $lieux = ["Salon A", "Salon B", "Salon C", "Salon D"];

        $annonces = [];
        for ($i = 0; $i < 30; $i++) {
            $anMatiere = $faker->randomElement($matieres);
            $anNiveau = $faker->randomElement($niveaux);
            $anStatusAnnonce = $faker->randomElement($status);

            $annonces[$i] = new Annonce();
            // Creation de creaneau pour cette annonce
            for ($y = 0; $y < $faker->numberBetween(1, 3); $y++) {
                $Creneau = new Creneau();
                $debutCourTimestamp = time() +  $faker->numberBetween($min = 60 * 60 * 24, $max = 60 * 60 * 24 * 14);
                $debutCour = (new \DateTime())->setTimestamp($debutCourTimestamp);
                $finCourTimestamp = $debutCourTimestamp +  $faker->numberBetween($min = 60 * 30, $max = 60 * 60 * 3);
                $finCour = (new \DateTime())->setTimestamp($finCourTimestamp);

                $Creneau
                    ->setdebutAt($debutCour)
                    ->setFinAt($finCour)
                    ->setAnnonce($annonces[$i]);
                $manager->persist($Creneau);
            }

            $annonces[$i]
                ->setTitre("recherche un enseignant de {$anMatiere->getNom()}")
                ->setDescription($faker->sentence($nbWords = 15, $variableNbWords = true))
                ->setPrix($faker->numberBetween(5, 150))
                ->setDureeEnMin($faker->numberBetween(10, 180))
                ->setLieux($lieux[array_rand($lieux)])
                ->setMatiere($anMatiere)
                ->setNiveau($anNiveau)
                ->setStatusAnnonce($anStatusAnnonce)
                ->setAuteur($faker->randomElement($users))
                ->setPresentiel($faker->randomElement($Presentiels));

            $manager->persist($annonces[$i]);
        }

        // Creation de commentaire
        $commentaires = [];
        for ($i = 0; $i < 300; $i++) {
            $source = $faker->randomElement($users);
            $tempUsers = $users;
            unset($tempUsers[array_search($source, $tempUsers)]);
            $destinataire = $faker->randomElement($tempUsers);

            $commentaires[$i] = new Commentaire();

            $commentaires[$i]
                ->setContenu($faker->paragraph(3, true))
                ->setNote($faker->numberBetween(1, 5))
                ->setSource($source)
                ->setDestinataire($destinataire);

            $manager->persist($commentaires[$i]);
        }

        // je fait candidater un utilisateur a toutes les annonces
        $commentaires = [];
        for ($i = 0; $i < 300; $i++) {
            $source = $faker->randomElement($users);
            $tempUsers = $users;
            unset($tempUsers[array_search($source, $tempUsers)]);
            $destinataire = $faker->randomElement($tempUsers);

            $commentaires[$i] = new Commentaire();

            $commentaires[$i]
                ->setContenu($faker->paragraph(3, true))
                ->setNote($faker->numberBetween(1, 5))
                ->setSource($source)
                ->setDestinataire($destinataire);

            $manager->persist($commentaires[$i]);
        }

        $manager->flush();
    }
}
