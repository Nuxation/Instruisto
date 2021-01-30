<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MessagerieController extends AbstractController
{
    /**
     * @Route("/messagerie", name="messagerie")
     */
    public function messagerie(): Response
    {
    	$connectedUser = $this->getUser();
    	$messageSources = $connectedUser->getMessagesSource();
    	$messageDestinataires = $connectedUser->getMessagesDestinataire();

    	$allMessages = array();

    	foreach ($messageSources as $messageSource) {
    		$allMessages[] = $messageSource;
    	}
    	foreach ($messageDestinataires as $messageDestinataire) {
    		$allMessages[] = $messageDestinataire;
    	}
    	uasort($allMessages, array($this, 'cmp1'));
    	
    	$idList = array();
    	$lastMessages = array();
    	foreach($allMessages as $message) {
    		$idUser = ($connectedUser->getId() == $message->getSource()->getId()) ? $message->getDestinataire()->getId() : $message->getSource()->getId();
    		if (!in_array($idUser, $idList)) {
    			$idList[] = $idUser;
    			$lastMessages[] = $message;
    		}
    	}

        return $this->render('messagerie/index.html.twig', [
        	'connectedUser' => $connectedUser,
            'lastMessages' => $lastMessages
        ]);
    }

    /**
     * @Route("/messagerie/{id}", name="messagerie_to")
     */
    public function messagerie_to($id, Request $request): Response
    {
    	$repository = $this->getDoctrine()->getRepository(User::class);
    	$otherUser = $repository->find($id);
    	$connectedUser = $this->getUser();

        $message = new Message($connectedUser, $otherUser);
    	$form = $this->createForm(MessageType::class, $message);
    	$form->add('submit', SubmitType::class, array('label' => 'Envoyer'));
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    		$entityManager = $this->getDoctrine()->getManager();
    		$entityManager->persist($message);
    		$entityManager->flush();
    	}

    	$messageSources = $connectedUser->getMessagesSource();
    	$messageDestinataires = $connectedUser->getMessagesDestinataire();

    	$allMessages = array();

    	foreach ($messageSources as $messageSource) {
    		$allMessages[] = $messageSource;
    	}
    	foreach ($messageDestinataires as $messageDestinataire) {
    		$allMessages[] = $messageDestinataire;
    	}

    	uasort($allMessages, array($this, 'cmp1'));
    	
    	$idList = array();
    	$lastMessages = array();
    	foreach($allMessages as $message) {
    		$idUser = ($connectedUser->getId() == $message->getSource()->getId()) ? $message->getDestinataire()->getId() : $message->getSource()->getId();
    		if (!in_array($idUser, $idList)) {
    			$idList[] = $idUser;
    			$lastMessages[] = $message;
    		}
    	}

    	uasort($allMessages, array($this, 'cmp2'));

    	$messagesWithId = array();

    	foreach ($allMessages as $message) {
    		$source = $message->getSource()->getId();
    		$destinataire = $message->getDestinataire()->getId();
    		if ($source == $connectedUser->getId() && $destinataire == $id || $source == $id && $destinataire == $connectedUser->getId()) {
    			$messagesWithId[] = $message;
    		}
    	}
        return $this->render('messagerie/index.html.twig', [
        	'messageForm' => $form->createView(),
        	'connectedUser' => $connectedUser,
            'lastMessages' => $lastMessages,
            'messagesWithId' => $messagesWithId
        ]);
    }

    function cmp1($a, $b) {
    	if ($a->getCreatedAt()==$b->getCreatedAt()){
    		return 0;
    	}
    	return ($a->getCreatedAt()<$b->getCreatedAt()) ? 1 : -1;
    }

        function cmp2($a, $b) {
    	if ($a->getCreatedAt()==$b->getCreatedAt()){
    		return 0;
    	}
    	return ($a->getCreatedAt()>$b->getCreatedAt()) ? 1 : -1;
    }
}
