<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentaireType;
use App\Entity\Commentaire;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/")
 * @IsGranted("ROLE_USER")
 */
class ConsultationProfilController extends AbstractController
{       
    /**
     * @Route("/consultation/profil/{id}", name="consultation_profil")
     */
    public function index(User $user, Request $request)
    {
        $user->setPassword('')
            ->setRoles([]);

        $userId = $this->getUser()->getId();

        $commentairesOnProfil = $this->getDoctrine()->getRepository(Commentaire::class)->findByDestinataire($user->getId());

        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findBySource($userId);
        foreach ($commentaire as $c) {
            if ($c->getDestinataire()->getId() == $user->getId()) {
                $commToDisplay = $c;
            }
        }
        if (isset($commToDisplay)) {
            $form = $this->createForm(CommentaireType::class, $commToDisplay);
            $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        } else {
            $commToDisplay = new Commentaire($this->getUser(),$user);
            $form = $this->createForm(CommentaireType::class, $commToDisplay);
            $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commToDisplay);
            $entityManager->flush();
        }

        return $this->render('consultation_profil/index.html.twig', [
            'commentaireForm' => $form->createView(),
            'user' => $user,
            'commentaires' => $commentairesOnProfil
        ]);
    }
}
