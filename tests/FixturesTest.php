<?php

namespace App\tests;

use App\Repository\AnnonceRepository;
use App\Repository\CommentaireRepository;
use App\Repository\MatiereRepository;
use App\Repository\NiveauRepository;
use App\Repository\PresentielRepository;
use App\Repository\StatusAnnonceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FixturesTest extends KernelTestCase
{
    public function testCountAnnonces(){
        self::bootKernel();
        $annonces = self::$container->get(AnnonceRepository::class)->count([]);
        $this->assertEquals(30, $annonces);
    }

    public function testCountUsers(){
        self::bootKernel();
         $users = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(31, $users);
    }

    public function testCountmatiersNom(){
        self::bootKernel();
        $matieres  = self::$container->get(MatiereRepository::class)->count([]);
        $this->assertEquals(8, $matieres);
    }

    public function testCountniveaux(){
        self::bootKernel();
        $niveaux  = self::$container->get(NiveauRepository::class)->count([]);
        $this->assertEquals(5, $niveaux);
    }

    public function testCountstatusNom(){
        self::bootKernel();
        $statut  = self::$container->get(StatusAnnonceRepository::class)->count([]);
        $this->assertEquals(3, $statut);
    }

    public function testCountPresentielNom(){
        self::bootKernel();
        $PresentielNom  = self::$container->get(PresentielRepository::class)->count([]);
        $this->assertEquals(2, $PresentielNom);
    }

    public function testCountcommentaires(){
        self::bootKernel();
        $commentaires  = self::$container->get(CommentaireRepository::class)->count([]);
        $this->assertEquals(600, $commentaires);
    }

}