<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultationProfilController extends AbstractController
{
    /**
     * @Route("/consultation/profil/{id}", name="consultation_profil")
     */
    public function index(User $user)
    {
        $user->setPassword('')
            ->setRoles([]);


        return $this->render('consultation_profil/index.html.twig', [
            'user' => $user,
        ]);
    }
}
