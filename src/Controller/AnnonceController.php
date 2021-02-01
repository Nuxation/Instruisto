<?php

namespace App\Controller;

use App\Entity\Matiere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Annonce;
use App\Entity\StatusCandidat;
use App\Entity\UtilisateurAnnonce;
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
		return $this->render('annonce/display.html.twig', array('annonce' => $annonce));
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
        $entityManager->persist($ua);
        $entityManager->flush();
        return $this->redirectToRoute('accueil');
    }

}
