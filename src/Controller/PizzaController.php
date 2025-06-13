<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use App\Services\PizzaService;



final class PizzaController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private PizzaService $pizzaService){
    }

    #[Route('/pizza', name: 'pizza', methods: ['GET'], format: 'json')]
    public function getAllPizzas(): JsonResponse
    {
        $pizzas = $this->pizzaService->getAllPizzas();

        if (empty($pizzas)) {
            return $this->json([
                'message' => 'No se encontraron pizzas.',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($pizzas, Response::HTTP_OK);
    }
}
