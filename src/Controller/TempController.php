<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TempController extends AbstractController
{
    /**
     * @Route("/", name="temp")
     */
    public function index(): Response
    {
        return $this->render('temp/index.html.twig', [
            'Nom' => "Coucou l'équipe Haspadar",
        ]);
    }
}
