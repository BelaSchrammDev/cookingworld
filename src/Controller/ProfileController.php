<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'app_profile.')]
class ProfileController extends BaseUserController
{
    #[Route('/show/{id}', name: 'show')]
    public function showProfile($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function editProfile(): Response
    {
        return $this->render('profile/edit.html.twig', [
        ]);
    }

    #[Route('/edit/picture', name: 'edit_picture')]
    public function editProfilePicture(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $selectPictureForm = $this->createFormBuilder()
            ->add('picture', FileType::class)
            ->getForm();
        $selectPictureForm->handleRequest($request);

        if ($selectPictureForm->isSubmitted() && $selectPictureForm->isValid()) {
            $picture = $request->files->get('recipe')['picture'];
            if ($picture) {
                $pictureName = md5(uniqid()) . '.' . $picture->guessExtension();
                $picture->move($this->getParameter('avatar_img_directory'), $pictureName);
                $user = $this->getUser();
                $user->setPicture($pictureName);
                $entityManager = $managerRegistry->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_profile.show', ['id' => $user->getId()]);
            }
        }

        $defaultPictures = [];
        if (is_dir($this->getParameter('default_avatar_img_directory'))) {
            $defaultPictures = array_diff(scandir($this->getParameter('default_avatar_img_directory')), ['..', '.']);
        }
        else {
            $this->addFlash('warning', 'Default avatar directory not found: ' . $this->getParameter('default_avatar_img_directory'));
        }

        dump($defaultPictures);

        return $this->render('profile/edit.picture.html.twig', [
            'defaultPictures' => $defaultPictures,
            'defaultPicturesDirectory' => 'img/users/default/',
            'selectPictureForm' => $selectPictureForm->createView(),
        ]);
    }
}
