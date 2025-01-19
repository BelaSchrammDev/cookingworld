<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_recipes.')]
class RecipesController extends BaseUserController
{
    #[Route('/listrecipes', name: 'list')]
    public function listRecipes(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();
        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipe/{id}', name: 'show')]
    public function showRecipe($id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);
        return $this->render('recipes/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/create-recipe', name: 'create')]
    public function createRecipe(Request $request, ManagerRegistry $mr, UserRepository $userRepository): Response
    {
        $newRecipe = new Recipe();
        $formRecipe = $this->createForm(RecipeType::class, $newRecipe);

        $formRecipe->handleRequest($request);
        if ($formRecipe->isSubmitted() && $formRecipe->isValid()) {
            // get the picture from the form
            $picture = $request->files->get('recipe')['picture'];
            if ($picture) {
                $pictureName = md5(uniqid()) . '.' . $picture->guessExtension();
                $picture->move($this->getParameter('recipes_img_directory'), $pictureName);
                $newRecipe->setPicture($pictureName);
            }

            // get the user from the session
            $user = $this->getUser();
            $user->addRecipe($newRecipe);
            $newRecipe->setCreator($this->getUser());

            $entityManager = $mr->getManager();
            $entityManager->persist($newRecipe);
            $entityManager->flush();

            $this->addFlash('success', 'Rezept wurde erfolgreich erstellt!');

            return $this->redirectToRoute('app_recipes.list');
        }

        return $this->render('recipes/edit.html.twig', [
            'form_recipe' => $formRecipe->createView(),
            'edit' => false,
        ]);
    }

    #[Route('/edit-recipe/{id}', name: 'edit')]
    public function editRecipe($id, RecipeRepository $recipeRepository, Request $request, ManagerRegistry $mr): Response
    {
        $recipe = $recipeRepository->find($id);
        $formRecipe = $this->createForm(RecipeType::class, $recipe);

        $formRecipe->handleRequest($request);
        
        if ($formRecipe->isSubmitted() && $formRecipe->isValid()) {
            $picture = $request->files->get('recipe')['picture'];

            if (isset($request->get('recipe')['removepicture'])) {
                $recipe->setPicture(null);
            } else if ($picture) {
                // check if there is already a picture
                $pictureName = md5(uniqid()) . '.' . $picture->guessExtension();
                $picture->move($this->getParameter('recipes_img_directory'), $pictureName);
                $recipe->setPicture($pictureName);
            }

            $entityManager = $mr->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Rezept wurde geändert!');

            return $this->redirectToRoute('app_recipes.list');
        }

        return $this->render('recipes/edit.html.twig', [
            'form_recipe' => $formRecipe->createView(),
            'edit' => true,
            'picture' => $recipe->getPicture(),
        ]);
    }
}
