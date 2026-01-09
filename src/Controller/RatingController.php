<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Repository\RatingRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/ratings', name: 'api_ratings_')]
class RatingController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        RatingRepository $ratingRepository,
        RecipeRepository $recipeRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // 1. Validar ID de receta (recipe_id)
        if (empty($data['recipe_id'])) {
            return $this->json(['error' => 'Recipe ID is required'], Response::HTTP_BAD_REQUEST);
        }

        // 2. Validar existencia y estado de la receta
        $recipe = $recipeRepository->find($data['recipe_id']);
        if (!$recipe || $recipe->getDeletedAt() !== null) {
             // "Que exista la receta que hay que votar." - Si se elimina, la validación falla.
            return $this->json(['error' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }

        // 3. Validar entrada de calificación
        if (!isset($data['rate'])) {
            return $this->json(['error' => 'Rate is required'], Response::HTTP_BAD_REQUEST);
        }

        $rate = $data['rate'];
        // "El voto debe de ser un entero entre 0 y 5."
        if (!is_int($rate) || $rate < 0 || $rate > 5) {
            return $this->json(['error' => 'Rate must be an integer between 0 and 5'], Response::HTTP_BAD_REQUEST);
        }

        // 4. Validar IP duplicada
        // "Cada votación tiene una IP asociada, no puede haber más de 1 voto con una misma IP"
        $ip = $request->getClientIp();

        // Nota: En dev local, la IP puede ser '127.0.0.1' o '::1'. 
        // Si se está comprobando la unicidad, se asegura de que los datos existentes (si se insertaron manualmente) lo manejen.
        if ($ratingRepository->hasUserRated($recipe->getId(), $ip)) {
             return $this->json(['error' => 'You have already voted for this recipe'], Response::HTTP_BAD_REQUEST); // Or 409 Conflict
        }

        // 5. Crear calificación
        $rating = new Rating();
        $rating->setRecipe($recipe);
        $rating->setRate($rate);
        $rating->setIp($ip);

        $em->persist($rating);
        $em->flush();

        return $this->json(['message' => 'Rating submitted successfully'], Response::HTTP_CREATED);
    }
}
