<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\CreneauRepository;
use App\Repository\AnnonceRepository;
use App\Form\ProfilType;
use App\Entity\User;

/**
 * @Route("/")
 * @IsGranted("ROLE_USER")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     * @IsGranted("ROLE_USER")
     */
    public function index(AnnonceRepository $annonceRepository, CreneauRepository $creneauRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);

        $annonces = $annonceRepository->findBy(['auteur' =>  $user]);

        
        $rdvs = [];
        foreach ($annonces as $annonce) {
            if ($annonce->getStatusAnnonce()->getnom() == "enseignant_validé" || $annonce->getStatusAnnonce()->getnom() == "cours_fini")
            {
                $rdvs[] = [
                    'id' => $annonce->getId(),
                    'start' => $creneauRepository->findOneBy(['annonce' => $annonce])->getDebutAt()->format(\DateTime::ISO8601),
                    'end' => $creneauRepository->findOneBy(['annonce' => $annonce])->getFinAt()->format(\DateTime::ISO8601),
                    'title' => $annonce->getTitre(),
                ];
            }
        }

        $data = json_encode($rdvs);

        return $this->render('profil/profil.html.twig', [
            'formProfil' => $form->createView(),
            'idUser' => $user->getId(),
            'prenomUser' => $user->getPrenom(),
            'image' => $user->getAvatar(),
            'data' => $data,
        ]);
    }

    /**
     * @Route("/profil/modifier/{id}", name="modifier_form")
     */
    public function modifier(User $user, Request $request, UserPasswordEncoderInterface $encoder,AnnonceRepository $annonceRepository, CreneauRepository $creneauRepository)
    {
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldUser = $this->getUser();
            // $oldUser = $this->getDoctrine()->getRepository(User::class)->find($user->getId());

            $avatarFile = $form->get('avatar')->getData();
            if ($form->get('password')->getData() == "") {
                $user->setPassword($oldUser->getPassword());
            } else {
                //dd($encoder->isPasswordValid($oldUser, "B"), $encoder->encodePassword($oldUser, $oldUser->getPassword()), $encoder->encodePassword($oldUser, $request->request->get('ancienPassword')));
                if ($encoder->isPasswordValid($user, $request->request->get('ancienPassword')) && $request->request->get('nvPassword') == $form->get('password')->getData()) {
                    $user->setPassword($encoder->encodePassword($user, $form->get('password')->getData()));
                } else {
                    $user->setPassword($encoder->encodePassword($oldUser, "B"));
                }
            }
            if ($avatarFile) {

                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugify($originalFilename);
                $newFilename = $user->getAvatar();
                if ($newFilename == null) {
                    $newFilename = $safeFilename;
                }
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();
                $user->setAvatar("/images_Profil/" . $newFilename);
                try {
                    $avatarFile->move(
                        $this->getParameter('Images_Profils'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        $annonces = $annonceRepository->findBy(['auteur' =>  $user]);
        
        $rdvs = [];
        foreach ($annonces as $annonce) {
            if ($annonce->getStatusAnnonce()->getnom() == "enseignant_validé" || $annonce->getStatusAnnonce()->getnom() == "cours_fini")
            {
                $rdvs[] = [
                    'id' => $annonce->getId(),
                    'start' => $creneauRepository->findOneBy(['annonce' => $annonce])->getDebutAt()->format(\DateTime::ISO8601),
                    'end' => $creneauRepository->findOneBy(['annonce' => $annonce])->getFinAt()->format(\DateTime::ISO8601),
                    'title' => $annonce->getTitre(),
                ];
            }
        }
        $data = json_encode($rdvs);
        
        return $this->render('profil/profil.html.twig', [
            'formProfil' => $form->createView(),
            'idUser' => $user->getId(),
            'prenomUser' => $user->getPrenom(),
            'image' => $user->getAvatar(),
            'data' => $data,
        ]);
    }
    private function slugify($string)
    {
        return preg_replace(
            '/[^a-z0-9]/',
            '-',
            strtolower(trim(strip_tags($string)))
        );
    }

    /**
     * @Route("/profil/supprimer/{id}", name="supprimer_compte")
     */
    public function supprimer(User $user, Request $request)
    {
        $personne = $this->getUser();
        //$personne = $this->getDoctrine()->getRepository(User::class)->find(6);
        $em = $this->getDoctrine()->getManager();
        $em->remove($personne);
        $em->flush();
        return $this->redirectToRoute('app_logout');
    }
}
