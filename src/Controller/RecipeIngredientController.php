<?php

namespace App\Controller;

use App\Entity\RecipeIngredient;
use App\Form\RecipeIngredientType;
use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipe/ingredient')]
final class RecipeIngredientController extends AbstractController
{
    #[Route(name: 'app_recipe_ingredient_index', methods: ['GET'])]
    public function index(RecipeIngredientRepository $recipeIngredientRepository): Response
    {
        return $this->render('recipe_ingredient/index.html.twig', [
            'recipe_ingredients' => $recipeIngredientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipeIngredient = new RecipeIngredient();
        $form = $this->createForm(RecipeIngredientType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipeIngredient);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe_ingredient/new.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_ingredient_show', methods: ['GET'])]
    public function show(RecipeIngredient $recipeIngredient): Response
    {
        return $this->render('recipe_ingredient/show.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecipeIngredient $recipeIngredient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeIngredientType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe_ingredient/edit.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, RecipeIngredient $recipeIngredient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeIngredient->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipeIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}
