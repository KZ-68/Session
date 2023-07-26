<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

     /* Attention : Si plusieurs view ont dans le nom de la route un identifiant et se trouve dans le même dossier
    il faut une ajouter une sépération supplémentaire après l'identifiant */
    #[Route('/matiere/{id}/show', name: 'show_matiere')]
    #[IsGranted('ROLE_USER')]
    public function showMatiere(Matiere $matiere): Response 
    {
        return $this->render('user/showMatiere.html.twig', [
            'matiere' => $matiere
        ]);
    }

    #[Route('/categorie/{id}/show', name: 'show_categorie')]
    #[IsGranted('ROLE_USER')]
    public function showCategorie(Categorie $categorie): Response 
    {
        return $this->render('user/showCategorie.html.twig', [
            'categorie' => $categorie
        ]);
    }

    #[Route('/formation', name: 'app_formation')]
    #[IsGranted('ROLE_USER')]
    public function formationsList(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findBy([], ["intituleFormation" => "ASC"]);
        
        return $this->render('user/formationsList.html.twig', [
            'formations' => $formations
        ]);
    }

    #[Route('/formation/{id}/show', name: 'show_formation')]
    #[IsGranted('ROLE_USER')]
    public function showformation(Formation $formation): Response 
    {
        return $this->render('user/showFormation.html.twig', [
            'formation' => $formation
        ]);
    }
}
