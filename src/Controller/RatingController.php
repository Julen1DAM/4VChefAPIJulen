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
            return $this->json(['error' => 'El ID de la receta es obligatorio'], Response::HTTP_BAD_REQUEST);
        }

        // 2. Validar existencia y estado de la receta
        $recipe = $recipeRepository->find($data['recipe_id']);
        if (!$recipe || $recipe->getDeletedAt() !== null) {
             // "Que exista la receta que hay que votar." - Si se elimina, la validación falla.
            return $this->json(['error' => 'La receta no existe'], Response::HTTP_NOT_FOUND);
        }

        // 3. Validar entrada de calificación
        if (!isset($data['rate'])) {
            return $this->json(['error' => 'La calificación es obligatoria'], Response::HTTP_BAD_REQUEST);
        }

        $rate = $data['rate'];
        // "El voto debe de ser un entero entre 0 y 5."
        if (!is_int($rate) || $rate < 0 || $rate > 5) {
            return $this->json(['error' => 'La calificación debe ser un número entre 0 y 5'], Response::HTTP_BAD_REQUEST);
        }

        // 4. Validar IP duplicada
        // "Cada votación tiene una IP asociada, no puede haber más de 1 voto con una misma IP"
        $ip = $request->getClientIp();

        // Nota: En dev local, la IP puede ser '127.0.0.1' o '::1'. 
        // Si se está comprobando la unicidad, se asegura de que los datos existentes (si se insertaron manualmente) lo manejen.
        if ($ratingRepository->hasUserRated($recipe->getId(), $ip)) {
             return $this->json(['error' => 'Ya has votado esta receta'], Response::HTTP_BAD_REQUEST); // Or 409 Conflict
        }

        // 5. Crear calificación
        $rating = new Rating();
        $rating->setRecipe($recipe);
        $rating->setRate($rate);
        $rating->setIp($ip);

        $em->persist($rating);
        $em->flush();

        return $this->json(['message' => 'Calificación enviada correctamente'], Response::HTTP_CREATED);
    }
}
