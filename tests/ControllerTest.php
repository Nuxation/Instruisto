<?php

namespace App\tests;

use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ControllerTest extends WebTestCase{

    public function testPaAgeAcueil(){
        $client = static::createClient();
        $client->request('GET', '/' );
        $this->assertSelectorTextContains('h3', 'Votre parcours vers la réussite');
    }

    public function testPaAgeProfil(){
        $client = static::createClient();
        $client->request('GET', '/profil' );
        $this->assertSelectorTextContains('div.nom', 'Bernard');
    }

}