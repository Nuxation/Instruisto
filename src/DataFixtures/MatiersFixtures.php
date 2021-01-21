<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Repository\MatiereRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MatiersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadMatiers($manager);



        $manager->flush();
    }

    public function loadMatiers($manager)
    {
        $faker = Factory::create();
        $matiers = ["Mathematiques", "Physique", "Chimie",
            "informatique", "Arts", "Philosophie"];

        $lieux = ["Salon A", "Salon B", "Salon C", "Salon D"];


        for($i = 0; $i < 10; $i++)
        {
            $matiere = new Matiere();
            $annonce = new Annonce();
            $niveau = new Niveau();

            $matiere->setNom($matiers[array_rand($matiers)]);
            $manager->persist($matiere);

            $niveau->setNom($faker->name);

            $annonce->setTitre($faker->name());
            $annonce->setDescription($faker->paragraph(3));
            $annonce->setPrix($faker->numberBetween(10, 1000));
            $annonce->setDureeEnMin($faker->numberBetween(10, 180));
            $annonce->setLieux($lieux[array_rand($lieux)]);
            $annonce->setMatiere($matiere);
            $annonce->setNiveau($niveau);

            $manager->persist($niveau);
            $manager->persist($annonce);


        }
    }


}
