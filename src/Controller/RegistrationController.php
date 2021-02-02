<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\AnnonceRepository;
use App\Repository\CreneauRepository;
use App\Repository\UserRepository;
use App\Security\AppCustomeAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomeAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/{id}", name="app_profile")
     */
    public function profile($id, UserRepository $userRepository, AnnonceRepository $annonceRepository, CreneauRepository $creneauRepository)
    {
        $user = $userRepository->find($id);

        // Pour le Calendrier :
        $annonces = $annonceRepository->find($user);

        $crenau = $creneauRepository->find($annonces);

        //dd($annonces->getStatusAnnonce()->getnom());

        $annonceNom = $annonces->getStatusAnnonce()->getnom();

            $start = $crenau->getDebutAt()->format(\DateTime::ISO8601);
            $end = $crenau->getFinAt()->format(\DateTime::ISO8601);
            $annonceTitre = $annonces->getTitre();



        if ( $annonceNom == "cours_fini" || $annonceNom == "enseignant_validé") {
            $crenau = $creneauRepository->find($annonces);
        }





        //dd($annonceTitre);


        return $this->render('registration/profile.html.twig', [
            'user' => $user,
            'creneau' => $crenau,
            'annonceTitre' => $annonceTitre,
            'start' => $start,
            'end' => $end
        ]);
    }
}
