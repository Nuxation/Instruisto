<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\User;

/**
 * @Route("/")
 * @IsGranted("ROLE_USER")
 */
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
