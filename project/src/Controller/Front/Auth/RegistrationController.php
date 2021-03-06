<?php

namespace App\Controller\Front\Auth;

use App\Entity\StaticStorage\UserRolesStorage;
use App\Entity\User;
use App\Form\Front\Auth\RegistrationType;
use App\Repository\UserRepository;
use App\Security\Front\Verifier\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier,
    ) {
    }

    #[Route('/register', name: 'front_register')]
    public function register(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($hasher->hashPassword($user, $plainPassword));
            $user->setRoles([UserRolesStorage::ROLE_USER]);

            $em->persist($user);
            $em->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'front_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('robot@example.com', 'Robot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('front/auth/registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Please Confirm your Email!');

            return $this->redirectToRoute('front_home');
        }

        return $this->render('front/auth/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'front_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('front_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('front_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('danger', $exception->getReason());

            return $this->redirectToRoute('front_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('front_login');
    }
}
