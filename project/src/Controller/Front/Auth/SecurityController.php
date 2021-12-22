<?php

namespace App\Controller\Front\Auth;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'front_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('front_profile_show');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('danger', $error->getMessageKey());
        }

        return $this->render('front/auth/security/login.html.twig', ['last_username' => $lastUsername]);
    }

    #[Route('/logout', name: 'front_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/logout_message', name: 'logout_message')]
    public function logoutMessage(Request $request): RedirectResponse
    {
        $this->addFlash('success', 'You are logged out!');

        return $this->redirectToRoute('front_home');
    }
}
