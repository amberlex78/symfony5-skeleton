<?php

namespace App\Controller\Front;

use App\Form\Front\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'front_profile_')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'show')]
    public function index(): Response
    {
        return $this->render('front/profile/show.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/edit', name: 'edit', methods: ['GET','POST'])]
    public function edit(EntityManagerInterface $em, UserPasswordHasherInterface $hasher, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($plainPassword = $form->get('plainPassword')->getData()) {
                $user->setPassword($hasher->hashPassword($user, $plainPassword));
            }
            $em->flush();

            $this->addFlash('success', 'Changes saved!');

            return $this->redirectToRoute('front_profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/profile/edit.html.twig', compact('user', 'form'));
    }
}
