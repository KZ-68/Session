<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findBy([], ["dateDebut" => "ASC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit_session(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$session) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new_session.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId()
        ]);
    }

    #[Route('/session/{id}/showProgramme', name: 'show_programme')]
    public function showProgramme(Session $session): Response 
    {
        return $this->render('session/showProgramme.html.twig', [
            'session' => $session
        ]);
    }

    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function deleteSession(Session $session, EntityManagerInterface $entityManager) {
        // Prépare la suppression d'une instance de l'objet 

        $entityManager->remove($session);
        // Exécute la suppression
        $entityManager->flush();

        return $this->redirectToRoute('app_session');
    }

    #[Route('/session/{id}/show', name: 'show_session')]
    public function showSession(Session $session): Response 
    {
        return $this->render('session/showSession.html.twig', [
            'session' => $session
        ]);
    }
}
