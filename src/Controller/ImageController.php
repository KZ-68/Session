<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function index(Image $image = null, Request $request): Response
    {
        if (!$image) {
            $image = new Image();
        }

        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);
        return $this->render('image/index.html.twig', [
            'form' => $form,
        ]);
    }
}
