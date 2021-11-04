<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Rate;
use App\Form\RateType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\User;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="recipe_index", methods={"GET"})
     */
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="recipe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_show", methods={"GET","POST"})
     */
    public function show(Recipe $recipe, Request $request): Response
    {
        $rate = new Rate();
        $rate->setRecipe($recipe);
        $form1 = $this->createForm(RateType::class, $rate);
        $form1->handleRequest($request);

        $comment = new Comment();
        $comment->setRecipe($recipe);
        if ($this->getUser()) {
            $comment->setAuthor($this->getUser());
        }
        $form2 = $this->createForm(CommentType::class, $comment);
        $form2->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rate);
            $entityManager->flush();

        }
        
        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
        ]);

    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_delete", methods={"POST"})
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
