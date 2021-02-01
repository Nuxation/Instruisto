<?php

namespace App\Controller;

use App\Entity\Matiere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Annonce;
use App\Entity\Creneau;
use App\Entity\StatusCandidat;
use App\Entity\StatusAnnonce;
use App\Entity\UtilisateurAnnonce;
use App\Entity\Message;
use App\Form\AnnonceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="index")
     */
    public function index() {
        $matiere = $this->getDoctrine()->getRepository(Matiere::class)->findAll();
        return $this->render('annonce/index.html.twig',['matieres' => $matiere]);
    }

    /**
     * @Route("/annonce/search", name="index_search_matiere")
     */
    public function search(Request $request) {
        $listMatiere = $this->getDoctrine()->getRepository(Matiere::class)->findAll();
        $matiere = $request->request->get('matiere');
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->findByMatiere($matiere);
        return $this->render('annonce/index.html.twig',['annonces' => $annonce,'matieres'=>$listMatiere, 'currentM'=>$matiere]);
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
}