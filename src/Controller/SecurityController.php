<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        
        if(isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] == 'http://localhost:8000/') {
            if($request->isMethod('POST')) {
                if ($request->request->get('raison', '') !== null && empty($request->request->get('raison', ''))) {
                    if 
                    (
                        $request->request->get('email', '') !== null && !empty($request->request->get('email', '')) &&
                        $request->request->get('password', '') !== null && !empty($request->request->get('password', ''))
                    ) 
                    {
                        strip_tags($request->request->get('email', ''));
                        strip_tags($request->request->get('password', ''));
                        
                        if ($this->getUser()) {
                            return $this->redirectToRoute('userProfile');
                        }
                    }
                }
                
            } else {
                $response = new Response();
                $response->headers->set('Content-Type', 'text/html');
                $response->setStatusCode(405);
                return $response;
            }
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
