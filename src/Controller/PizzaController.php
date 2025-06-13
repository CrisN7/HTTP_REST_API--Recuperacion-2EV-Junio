<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use App\Services\PizzaService;



final class PizzaController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private PizzaService $pizzaService){
    }

    #[Route('/pizza', name: 'pizza', methods: ['GET'], format: 'json')]
    public function getAllPizzas(
        #[MapQueryParameter] ?string $name = null,
        #[MapQueryParameter] ?string $ingredients = null
    ): JsonResponse
    {
        $ingredientsFilterArray = [];
        if ($ingredients) {
            $ingredientsFilterArray = array_map('trim', explode(',', strtolower($ingredients)));
        }

        $pizzas = $this->pizzaService->getAllPizzas();

        if (empty($pizzas)) {
            return $this->json([
                'message' => 'No se encontraron pizzas.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($name) {
            $nameLower = strtolower($name);
            $pizzas = array_filter($pizzas, fn($pizza) => strpos(strtolower($pizza->title), $nameLower) !== false);
        }

        if (!empty($ingredientsFilterArray)) {
            $pizzas = array_filter($pizzas, function($pizza) use ($ingredientsFilterArray) {
                $pizzaIngredientNames = array_map(fn($ing) => strtolower($ing->name), $pizza->ingredients);

                foreach ($ingredientsFilterArray as $searchIngredient) {
                    foreach ($pizzaIngredientNames as $ingredientName) {
                        if (str_contains($ingredientName, $searchIngredient)) {
                            return true; // Al menos un ingrediente coincide parcialmente
                        }
                    }
                }

                return false; // Ningún ingrediente coincide parcialmente
            });
        }


        if (empty($pizzas)) {
            return $this->json([
                'message' => 'No se encontraron pizzas con esos criterios.',
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $pizzas = array_values($pizzas);

        return $this->json($pizzas, Response::HTTP_OK);
    }
}
