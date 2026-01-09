<?php

namespace App\Controller;

use App\Repository\NutrientTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/nutrient-types', name: 'api_nutrient_types_')]
class NutrientTypeController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(NutrientTypeRepository $nutrientTypeRepository): JsonResponse
    {
        $nutrients = $nutrientTypeRepository->findAll();

        $data = [];
        foreach ($nutrients as $nutrient) {
            // Si se ha eliminado, no se muestra
            if (method_exists($nutrient, 'getDeletedAt') && $nutrient->getDeletedAt() !== null) {
                continue;
            }

            $data[] = [
                'id' => $nutrient->getId(),
                'name' => $nutrient->getName(),
                'unit' => $nutrient->getUnit(),
            ];
        }

        return $this->json($data);
    }
}
