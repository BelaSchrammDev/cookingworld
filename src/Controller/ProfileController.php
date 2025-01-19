<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'app_profile.')]
class ProfileController extends AbstractController
{
    #[Route('/show/{id}', name: 'show')]
    public function showProfile($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        return $this->render('profile/show.html.twig', [
            'user'=> $user,
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function editProfile(): Response
    {
        return $this->render('profile/edit.html.twig', [
        ]);
    }
}
