<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\AnnonceType;
use App\Entity\UtilisateurAnnonce;
use App\Entity\User;
use App\Entity\StatusCandidat;
use App\Entity\StatusAnnonce;
use App\Entity\Message;
use App\Entity\Matiere;
use App\Entity\Creneau;
use App\Entity\Annonce;

/**
 * @Route("/")
 * @IsGranted("ROLE_USER")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="index")
     *
     */
    public function index() {
        $matiere = $this->getDoctrine()->getRepository(Matiere::class)->findAll();
        $a = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
        $statutAnnonce = $this->getDoctrine()->getRepository(StatusAnnonce::class)->findByNom("recherche_enseignant");
        $annonces = array();
        foreach ($a as $annonce) {
            if ($annonce->getStatusAnnonce()->getId() == $statutAnnonce[0]->getId()) {
                $annonces[] = $annonce;
            }
        }
        $annoncesToDisplay = array();
        for ($i = 0; $i < 5; $i++) {
            if (isset($annonces[$i])) {
                $annoncesToDisplay[] = $annonces[$i];
            }
        }
        return $this->render('annonce/index.html.twig',[
            'matieres' => $matiere, 
            'annonces' => $annoncesToDisplay,
            'currentPage' => 1,
            'nbPages' => ceil(sizeof($annonces) / 5),
            'currentM' => "all"
        ]);
    }

    /**
     * @Route("/annonce/search", name="index_search_matiere")
     */
    public function search(Request $request) {
        $matiere = $request->request->get('matiere');
        
        return $this->redirectToRoute('index_search_matiere_2', array(
            'matiere' => $matiere,
             'page' => 1
        ));
    }

        /**
     * @Route("/annonce/search/{matiere}/{page}", name="index_search_matiere_2")
     */
    public function search2($matiere, $page, Request $request) {
        $listMatiere = $this->getDoctrine()->getRepository(Matiere::class)->findAll();
        if ($matiere=="all") {
            $a = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
        }
        else {
            $a = $this->getDoctrine()->getRepository(Annonce::class)->findByMatiere($matiere);
        }

        $statutAnnonce = $this->getDoctrine()->getRepository(StatusAnnonce::class)->findByNom("recherche_enseignant");
        $annonces = array();
        foreach ($a as $annonce) {
            if ($annonce->getStatusAnnonce()->getId() == $statutAnnonce[0]->getId()) {
                $annonces[] = $annonce;
            }
        }

        $nbAnnonces = sizeof($annonces);
        $nbPages = ceil($nbAnnonces / 5);
        $annoncesToDisplay = array();
        if (isset($page)) {
            if ($page <= $nbPages) {
            for ($i = 5 * ($page - 1); $i < 5 * $page; $i++) {
                if (isset($annonces[$i])) {
                    $annoncesToDisplay[] = $annonces[$i];
                }
            }
        }
        else {
            $page = 1;
        }
        }
        return $this->render('annonce/index.html.twig',[
            'annonces' => $annoncesToDisplay,
            'matieres'=>$listMatiere, 
            'currentM'=>$matiere, 
            'currentPage' => $page, 
            'nbPages' => $nbPages]);
    }


    /**
     * @Route("/annonce/add", name="annonce_add")
     */
    public function add(Request $request): Response
    {
        $annonce = new Annonce;
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setAuteur($this->getUser());
            $statut = $this->getDoctrine()->getRepository(StatusAnnonce::class)->findByNom("recherche_enseignant");
            $annonce->setStatusAnnonce($statut[0]);
            $entityManager->persist($annonce);
            
            $entityManager->flush();
            
            for ($i=0; $i <  count($annonce->getCreneaus()); $i++) { 
                $annonce->getCreneaus()[$i]->setAnnonce($annonce);
            }
            $entityManager->flush();
            return $this->redirectToRoute('annonce_display', array('id' => $annonce->getId()));
        }
        return $this->render('annonce/add.html.twig',
            array(
                'annonceForm' => $form->createView(),
                'action' => 'Créer une annonce'
            ));

    }

    /**
     * @Route("/annonce/update/{id}", name="annonce_update")
     */
    public function update($id, Request $request): Response
    {
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();
            return $this->redirectToRoute('annonce_display', array('id' => $id));
        }
        return $this->render('annonce/add.html.twig',
            array(
                'annonceForm' => $form->createView(),
                'action' => 'Modifier une annonce'
            ));
    }

    /**
     * @Route("/annonce/display/{id}", name="annonce_display")
     */
    public function display($id) {
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);
        if(!$annonce)
            throw $this->createNotFoundException('Annonce [id='.$id.'] inexistante');
        $ua = $this->getDoctrine()->getRepository(UtilisateurAnnonce::class)->findByAnnonce($annonce);
        $aPostulé = false;
        foreach ($ua as $u) {
            if ($u->getCandidat()->getId() == $this->getUser()->getId()) {
                $aPostulé = true;
            }
        }
        $creneaux = $this->getDoctrine()->getRepository(Creneau::class)->findByAnnonce($annonce);
        return $this->render('annonce/display.html.twig', array('annonce' => $annonce, 'aPostulé' => $aPostulé, 'creneaux' => $creneaux));
    }

    /**
     * @Route("/annonce/delete/{id}", name="annonce_delete")
     */
    public function delete($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);
        $entityManager->remove($annonce);
        $entityManager->flush();
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/annonce/postuler/{id}", name="annonce_postuler")
     */
    public function postulerAnnonce($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);
        $auteurAnnonce = $annonce->getAuteur();
        $postuleurAnnonce = $this->getUser();
        $statut = $this->getDoctrine()->getRepository(StatusCandidat::class)->findByNom("En attente");
        $ua = new UtilisateurAnnonce();
        $ua->setCandidat($postuleurAnnonce);
        $ua->setAnnonce($annonce);
        $ua->setStatusCandidat($statut[0]);

        $message = new Message($postuleurAnnonce, $auteurAnnonce);
        $message->setContenu($postuleurAnnonce." se propose en tant que tuteur de ".$auteurAnnonce." pour l'annonce suivante : ".$annonce->getTitre());

        $entityManager->persist($ua);
        $entityManager->persist($message);
        $entityManager->flush();
        return $this->redirectToRoute('annonce_display', [
            'id' => $id
        ]);
    }

    /**
     * @Route("/annonce/desister/{id}", name="annonce_desister")
     */
    public function desisterAnnonce($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);
        $auteurAnnonce = $annonce->getAuteur();
        $postuleurAnnonce = $this->getUser();
        $ua = $this->getDoctrine()->getRepository(UtilisateurAnnonce::class)->findByAnnonce($annonce);
        foreach ($ua as $u) {
            if ($u->getCandidat()->getId() == $this->getUser()->getId()) {
                $entityManager->remove($u);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('annonce_display', [
            'id' => $id
        ]);
    }

    /**
     * @Route("/annonce/choixAnnonces", name="choix_annonces")
     */
    public function mesAnnonces() {
        return $this->render('annonce/choixAnnonces.html.twig');
    }

    /**
     * @Route("/annonce/choixAnnonces/publiées", name="user_annonces_publiées")
     */
    public function userAnnoncesPubliées() {
        $user = $this->getUser();

        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findByAuteur($user);
        $annoncesPubliéesEleve = array();
        foreach ($annonces as $annonce) {
            if ($annonce->getStatusAnnonce()->getNom() == "recherche_enseignant") {
                $annoncesPubliéesEleve[] = $annonce;
            }
        }

        $ua = $this->getDoctrine()->getRepository(UtilisateurAnnonce::class)->findByCandidat($user);
        $annoncesPubliéesTuteur = array();
        foreach ($ua as $uaf) {
            if ($uaf->getAnnonce()->getStatusAnnonce()->getNom() == "recherche_enseignant") {
                if ($uaf->getStatusCandidat()->getNom() != "Rejeté") {
                    $annoncesPubliéesTuteur[] = $uaf->getAnnonce();
                }
            }
        }

        return $this->render('annonce/annoncesPubliées.html.twig', [
            'annoncesPubliéesEleve'=>$annoncesPubliéesEleve,
            'annoncesPubliéesTuteur'=>$annoncesPubliéesTuteur
        ]);
    }

    /**
     * @Route("/annonce/choixAnnonces/validées", name="user_annonces_validées")
     */
    public function userAnnoncesValidées() {
        $user = $this->getUser();
        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findByAuteur($user);
        $annoncesValidéesEleve = array();
        foreach ($annonces as $annonce) {
            if ($annonce->getStatusAnnonce()->getNom() == "enseignant_validé") {
                $annoncesValidéesEleve[] = $annonce;
            }
        }

        $ua = $this->getDoctrine()->getRepository(UtilisateurAnnonce::class)->findByCandidat($user);
        $annoncesValidéesTuteur = array();
        foreach ($ua as $uaf) {
            if ($uaf->getAnnonce()->getStatusAnnonce()->getNom() == "enseignant_validé") {
                if ($uaf->getStatusCandidat()->getNom() != "Rejeté") {
                    $annoncesValidéesTuteur[] = $uaf->getAnnonce();
                }
            }
        }
        return $this->render('annonce/annoncesValidées.html.twig', [
            'annoncesValidéesEleve'=>$annoncesValidéesEleve,
            'annoncesValidéesTuteur'=>$annoncesValidéesTuteur
        ]);
    }

    /**
     * @Route("/annonce/choixAnnonces/passées", name="user_annonces_passées")
     */
    public function userAnnoncesPassées() {
        $user = $this->getUser();
        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findByAuteur($user);
        $annoncesPasséesEleve = array();
        foreach ($annonces as $annonce) {
            if ($annonce->getStatusAnnonce()->getNom() == "cours_fini") {
                $annoncesPasséesEleve[] = $annonce;
            }
        }

        $ua = $this->getDoctrine()->getRepository(UtilisateurAnnonce::class)->findByCandidat($user);
        $annoncesPasséesTuteur = array();
        foreach ($ua as $uaf) {
            if ($uaf->getAnnonce()->getStatusAnnonce()->getNom() == "cours_fini") {
                if ($uaf->getStatusCandidat()->getNom() != "Rejeté") {
                    $annoncesPasséesTuteur[] = $uaf->getAnnonce();
                }
            }
        }
        return $this->render('annonce/annoncesPassées.html.twig', [
            'annoncesPasséesEleve'=>$annoncesPasséesEleve,
            'annoncesPasséesTuteur'=>$annoncesPasséesTuteur
        ]);
    }

    /**
     * @Route("/annonce/{id}/candidatures", name="candidatures")
     */
    public function candidatures($id) {
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);
        $candidatsua = $annonce->getUtilisateurAnnonces();
        $candidatsUsers = array();
        foreach ($candidatsua as $candidatua) {
            $candidatsUsers[] = $candidatua->getCandidat();
        }
        return $this->render('annonce/candidats.html.twig', [
            'annonce'=>$annonce,
            'candidats'=>$candidatsUsers
        ]);
    }

    /**
     * @Route("/annonce/{id}/accepterCandidature/{candidatId}", name="accepter_candidature")
     */
    public function accepterCandidature($id, $candidatId) {
        $statutValidé = $this->getDoctrine()->getRepository(StatusCandidat::class)->findByNom("Validé");
        $statutRejeté = $this->getDoctrine()->getRepository(StatusCandidat::class)->findByNom("Rejeté");

        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);
        $candidat = $this->getDoctrine()->getRepository(User::class)->find($candidatId);

        if (!is_null($annonce) && !is_null($candidat)) {
            $candidatsua = $annonce->getUtilisateurAnnonces();
            $candidatsUsers = array();
            foreach ($candidatsua as $candidatua) {
                $idCandidat = $candidatua->getCandidat()->getId();
                if ($idCandidat == $candidatId) {
                    $candidatua->setStatusCandidat($statutValidé[0]);
                }
                else {
                    $candidatua->setStatusCandidat($statutRejeté[0]);
                }
            }

            $statutAnnonce = $this->getDoctrine()->getRepository(StatusAnnonce::class)->findByNom("enseignant_validé");
            $annonce->setStatusAnnonce($statutAnnonce[0]);

            $auteurAnnonce = $this->getUser();
            $candidatAccepté = $candidat;
            $message = new Message($auteurAnnonce, $candidatAccepté);
            $message->setContenu($candidatAccepté." a été accepté en tant que tuteur de ".$auteurAnnonce." pour l'annonce suivante : ".$annonce->getTitre());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('user_annonces_validées');
        }
        return $this->redirectToRoute('candidatures', [
            'id' => $id
        ]);
    }

    /**
     * @Route("/annonce/archiver/{id}", name="archiver_annonce")
     */
    public function archiverAnnonce($id) {
        $statutAnnonce = $this->getDoctrine()->getRepository(StatusAnnonce::class)->findByNom("cours_fini");

        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);

        if (!is_null($annonce)) {
            $annonce->setStatusAnnonce($statutAnnonce[0]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('user_annonces_passées');
        }
        return $this->redirectToRoute('candidatures', [
            'id' => $id
        ]);
    }
}
