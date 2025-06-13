<?php
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Order;
use App\Model\OrderNewDTO;
use App\Entity\Pizza;
use App\Entity\PizzaOrder;
use App\Model\OrderResponseDTO;
use Doctrine\Common\Collections\Collection;


class OrderService
{

    public function __construct(private LoggerInterface $logger, private EntityManagerInterface $entityManager)
    {
        
    }
    
    public function createOrder(OrderNewDTO $orderNewDTO): OrderResponseDTO
    {
        $order = new Order();
        $order->setDeliveryTime($orderNewDTO->delivery_time);
        $order->setDeliveryAddress($orderNewDTO->delivery_address);
        $order->setPaymentType($orderNewDTO->payment->payment_type);
        $order->setNumber($orderNewDTO->payment->number);

        foreach ($orderNewDTO->pizzas_order as $pizzaOrderDTO) {
            $pizza = $this->entityManager->getRepository(Pizza::class)->find($pizzaOrderDTO->pizza_id);

            $pizzaOrder = new PizzaOrder();
            $pizzaOrder->setPizza($pizza);
            $pizzaOrder->setQuantity($pizzaOrderDTO->quantity);
            $pizzaOrder->setOrderRef($order);

            $order->addPizzaOrder($pizzaOrder);
            $this->entityManager->persist($pizzaOrder);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $pizzas_order = array_map(function (PizzaOrder $po) {
            $pizza = $po->getPizza();
            $ingredientsDTO = array_map(fn($ingredient) => ['name' => $ingredient->getName()], $pizza->getIngredients()->toArray());

            $pizzaDTO = [
                'id' => $pizza->getId(),
                'title' => $pizza->getTitle(),
                'image' => $pizza->getImage(),
                'price' => $pizza->getPrice(),
                'ok_celiacs' => $this->checkCeliacs($pizza->getIngredients()),
                'ingredients' => $ingredientsDTO,
            ];

            return [
                'quantity' => $po->getQuantity(),
                'pizza_type' => $pizzaDTO
            ];
        }, $order->getPizzaOrders()->toArray());

        return new OrderResponseDTO(
            id: $order->getId(),
            pizzas_order: $pizzas_order
        );
    }

    public function getPizzaById(int $id): ?Pizza
    {
        return $this->entityManager->getRepository(Pizza::class)->find($id);
    }

    private function checkCeliacs(Collection $ingredients): bool
    {
        // Lista de ingredientes no aptos para celiacos
        $nonCeliacIngredients = ['Pepperoni', 'Jamón York']; 

        foreach ($ingredients as $ingredient) {
            if (in_array($ingredient->getName(), $nonCeliacIngredients, true)) {
                return false; // Hay un ingrediente que no es apto
            }
        }
        return true; // Todos son aptos
    }
    

}
