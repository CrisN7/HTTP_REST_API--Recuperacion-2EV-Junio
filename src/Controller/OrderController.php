<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use App\Model\OrderNewDTO;
use App\Services\OrderService;
use App\Model\ErrorDTO;

final class OrderController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private OrderService $orderService){
    }

    #[Route('/order', name: 'app_order_create', methods: ['POST'], format: 'json')]
    public function createOrder(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_NOT_FOUND)] OrderNewDTO $orderNewDTO
    ): JsonResponse {

        foreach ($orderNewDTO->pizzas_order as $pizzaOrderDTO) {
            $pizza = $this->orderService->getPizzaById($pizzaOrderDTO->pizza_id);
            
            if (!$pizza) {
                $this->logger->warning("Pizza con ID {$pizzaOrderDTO->pizza_id} no encontrada.");

                // Devolvemos un error 404 con mensaje
                $error = new ErrorDTO('20', 'Pizza con ID ' . $pizzaOrderDTO->pizza_id .' no encontrada.');
                return $this->json($error, 400);
            }
        }

        // Validar campos obligatorios
        if (empty($orderNewDTO->delivery_time)) {
            $error = new ErrorDTO('21', 'La hora de entrega es obligatoria.');
            return $this->json($error, 400);
        }

        if (empty($orderNewDTO->delivery_address)) {
            $error = new ErrorDTO('22', 'La dirección de entrega es obligatoria.');
            return $this->json($error, 400);
        }

        if (empty($orderNewDTO->payment) || empty($orderNewDTO->payment->payment_type)) {
            $error = new ErrorDTO('23', 'El pago es obligatorio.');
            return $this->json($error, 400);
        }

        // Validamos los tipos de pago
        $paymentType = strtolower($orderNewDTO->payment->payment_type);

        if (!in_array($paymentType, ['credit-card', 'bizum'])) {
            $error = new ErrorDTO('24', 'El tipo de pago debe ser "credit_card" o "bizum".');
            return $this->json($error, 400);
        }

        // Validar información del pago según tipo
        if ($paymentType === 'credit-card') {
            $cardNumber = $orderNewDTO->payment->number ?? '';
            if (!preg_match('/^\d{4}-\d{4}-\d{4}-\d{4}$/', $cardNumber)) {
                $error = new ErrorDTO('25', 'Número de tarjeta inválido. Formato esperado: XXXX-XXXX-XXXX-XXXX.');
                return $this->json($error, 400);
            }
        }

        if ($paymentType === 'bizum') {
            $phoneNumber = $orderNewDTO->payment->number ?? '';
            if (!preg_match('/^\d{9}$/', $phoneNumber)) {
                $error = new ErrorDTO('26', 'Número de teléfono inválido para Bizum. Debe tener 9 dígitos.');
                return $this->json($error, 400);
            }
        }

        $orderCreated = $this->orderService->createOrder($orderNewDTO);

        if (!$orderCreated) {
            return $this->json([
                'message' => 'Error al crear la orden',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($orderCreated, Response::HTTP_CREATED);
    }
}
