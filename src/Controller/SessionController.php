<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Form\ProgrammesSessionType;
use App\Form\StagiairesSessionType;
use App\Repository\MatiereRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
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
        $currentSessions = $sessionRepository->findCurrentSessionsByDate();
        $nextSessions = $sessionRepository->findNextSessionsByDate();
        $oldSessions = $sessionRepository->findOldSessionsByDate();

        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
            'currentSessions' => $currentSessions,
            'nextSessions' => $nextSessions,
            'oldSessions' => $oldSessions
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
            'form' => $form,
            'sessionId' => $session->getId()
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

    #[Route('/session/{id}/show/editStagiaires', name: 'edit_stagiaires_session')]
    public function edit_stagiaires_session(Session $session, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(StagiairesSessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }

        return $this->render('session/edit_stagiaires_session.html.twig', [
            'formEditStagiairesSession' => $form,
            'edit' => $session->getId()
        ]);
    }

    #[Route('/session/{id}/show/editProgrammes', name: 'edit_programmes_session')]
    public function edit_programmes_session(Session $session, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(ProgrammesSessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }

        return $this->render('session/edit_programmes_session.html.twig', [
            'form' => $form,
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

    #[Route('/session/{id}/show', name: 'show_session')]
    public function showSession(Session $session, StagiaireRepository $stagiaireRepository, MatiereRepository $matiereRepository): Response 
    {
        $stagiaires = $stagiaireRepository->findNonRegisteredStagiairesInSession($session->getId());
        $matieres = $matiereRepository->findNonRegisteredMatieresInSession($session->getId());
        return $this->render('session/showSession.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'matieres' => $matieres
        ]);
    }
}
