<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Programme;
use App\Repository\SessionRepository;
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

    #[Route('/session/{id}', name: 'show_programme')]
    public function show(Programme $programme): Response 
    {
        return $this->render('session/show.html.twig', [
            'programme' => $programme
        ]);
    }

    #[Route('/session/formation/{id}', name: 'showFormation_session')]
    public function showFormation(Formation $formation): Response 
    {
        return $this->render('session/showFormation.html.twig', [
            'formation' => $formation
        ]);
    }
}
