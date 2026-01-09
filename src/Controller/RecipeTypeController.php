<?php

namespace App\Controller;

use App\Repository\RecipeTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recipe-types', name: 'api_recipe_types_')]
class RecipeTypeController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(RecipeTypeRepository $recipeTypeRepository): JsonResponse
    {
        $types = $recipeTypeRepository->findAll();

        $data = [];
        foreach ($types as $type) {
            // "Si se ha eliminado, no se muestra"
            if (method_exists($type, 'getDeletedAt') && $type->getDeletedAt() !== null) {
                continue;
            }
            
            $data[] = [
                'id' => $type->getId(),
                'name' => $type->getName(),
                'description' => $type->getDescription(),
            ];
        }

        return $this->json($data);
    }
}
