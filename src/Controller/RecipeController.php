<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeNutrient;
use App\Entity\Step;
use App\Repository\NutrientTypeRepository;
use App\Repository\RecipeRepository;
use App\Repository\RecipeTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recipes', name: 'api_recipes_')]
class RecipeController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(RecipeRepository $recipeRepository): JsonResponse
    {
        // Filtra las recetas que no estén eliminadas
        $recipes = $recipeRepository->findAllActive();

        $data = [];
        foreach ($recipes as $recipe) {
            $data[] = [
                'id' => $recipe->getId(),
                'title' => $recipe->getTitle(),
                'number_diner' => $recipe->getNumberDiner(),
                'recipe_type' => $recipe->getRecipeType()->getName(),
                'ingredients_count' => count($recipe->getIngredients()),
                'steps_count' => count($recipe->getSteps()),
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, RecipeRepository $recipeRepository, EntityManagerInterface $em): JsonResponse
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            return $this->json(['error' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }

        if ($recipe->getDeletedAt()) {
             // Si la receta ya está eliminada, podría devolver 404 o éxito. 
             // Devuelve 404 como solicitado "validar que la receta a eliminar existe" implica excluir las recetas eliminadas si las tratamos como ausentes.
             return $this->json(['error' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }

        $recipe->setDeletedAt(new \DateTime());
        $em->flush();

        return $this->json(['message' => 'Recipe deleted successfully'], Response::HTTP_OK);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request, 
        EntityManagerInterface $em, 
        RecipeTypeRepository $recipeTypeRepository,
        NutrientTypeRepository $nutrientTypeRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validaciones básicas
        if (empty($data['title']) || empty($data['number_diner'])) {
            return $this->json(['error' => 'Title and number of diners are required'], Response::HTTP_BAD_REQUEST);
        }

        if (empty($data['recipe_type_id'])) {
             return $this->json(['error' => 'Recipe Type ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $recipeType = $recipeTypeRepository->find($data['recipe_type_id']);
        if (!$recipeType) {
            return $this->json(['error' => 'Invalid Recipe Type'], Response::HTTP_BAD_REQUEST);
        }

        // Validación de ingredientes (al menos 1)
        if (empty($data['ingredients']) || !is_array($data['ingredients']) || count($data['ingredients']) < 1) {
            return $this->json(['error' => 'At least one ingredient is required'], Response::HTTP_BAD_REQUEST);
        }

        // Validación de pasos (al menos 1)
        if (empty($data['steps']) || !is_array($data['steps']) || count($data['steps']) < 1) {
            return $this->json(['error' => 'At least one step is required'], Response::HTTP_BAD_REQUEST);
        }

        // Crear nueva receta
        $recipe = new Recipe();
        $recipe->setTitle($data['title']);
        $recipe->setNumberDiner($data['number_diner']);
        $recipe->setRecipeType($recipeType);

        $em->persist($recipe);

        // Añadir ingredientes
        foreach ($data['ingredients'] as $ingData) {
            if (empty($ingData['name']) || empty($ingData['quantity']) || empty($ingData['unit'])) {
                 return $this->json(['error' => 'Invalid ingredient data. Name, quantity, and unit are required.'], Response::HTTP_BAD_REQUEST);
            }
            $ingredient = new Ingredient();
            $ingredient->setName($ingData['name']);
            $ingredient->setQuantity($ingData['quantity']);
            $ingredient->setUnit($ingData['unit']);
            $ingredient->setRecipe($recipe);
            $em->persist($ingredient);
        }

        // Añadir pasos
        foreach ($data['steps'] as $stepData) {
            if (empty($stepData['description']) || !isset($stepData['order_step'])) {
                return $this->json(['error' => 'Invalid step data. Description and order_step are required.'], Response::HTTP_BAD_REQUEST);
            }
            $step = new Step();
            $step->setDescription($stepData['description']);
            $step->setOrderStep($stepData['order_step']);
            $step->setRecipe($recipe);
            $em->persist($step);
        }

        // Añadir nutrientes (lista opcional, pero validada si está presente)
        if (!empty($data['nutrients']) && is_array($data['nutrients'])) {
            foreach ($data['nutrients'] as $nutData) {
                if (empty($nutData['nutrient_type_id']) || empty($nutData['quantity'])) {
                    return $this->json(['error' => 'Invalid nutrient data'], Response::HTTP_BAD_REQUEST);
                }

                $nutrientType = $nutrientTypeRepository->find($nutData['nutrient_type_id']);
                if (!$nutrientType) {
                    return $this->json(['error' => 'Invalid Nutrient Type ID: ' . $nutData['nutrient_type_id']], Response::HTTP_BAD_REQUEST);
                }

                $recipeNutrient = new RecipeNutrient();
                $recipeNutrient->setNutrientType($nutrientType);
                $recipeNutrient->setQuantity($nutData['quantity']);
                $recipeNutrient->setRecipe($recipe);
                $em->persist($recipeNutrient);
            }
        }

        $em->flush();

        return $this->json(['message' => 'Recipe created successfully', 'id' => $recipe->getId()], Response::HTTP_CREATED);
    }
}
