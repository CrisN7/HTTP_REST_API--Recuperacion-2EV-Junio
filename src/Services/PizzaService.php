<?php
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Pizza;
use App\Entity\Ingredient;
use App\Model\PizzaDTO;
use App\Model\IngredientDTO;

class PizzaService
{

    public function __construct(private LoggerInterface $logger, private EntityManagerInterface $entityManager)
    {

        //Cargamos ingredientes si no hay ninguno
        if (sizeof($this->entityManager->getRepository(Ingredient::class)->findAll()) < 1) {
            $mockIngredients = [];

            $ingredient = new Ingredient();
            $ingredient->setName('Tomate');
            $ingredient->setPizza(null); // Aseguramos que el ingrediente no esté asociado a ninguna pizza
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Mozzarella');
            $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Pepperoni');
            $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Jamón York');
            $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Aceitunas');
            $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Cebolla');
            $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Champiñones');
            $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Pimiento Verde');
            $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            foreach ($mockIngredients as $ing) {
                $entityManager->persist($ing);
            }

            $this->entityManager->flush();
        }

        // Solo insertamos si no hay pizzas
        if (sizeof($this->getAllPizzas()) < 1) {
            $mockPizzas = [];

            $pizza = new Pizza();
            $pizza->setTitle('Margarita');
            $pizza->setImage('margarita.jpg');
            $pizza->setPrice(7.50);
            $pizza->setOkCeliacs(true);
            $mockPizzas[] = $pizza;

            $pizza = new Pizza();
            $pizza->setTitle('Cuatro Quesos');
            $pizza->setImage('cuatro_quesos.jpg');
            $pizza->setPrice(8.00);
            $pizza->setOkCeliacs(false);
            $mockPizzas[] = $pizza;

            $pizza = new Pizza();
            $pizza->setTitle('Pepperoni');
            $pizza->setImage('pepperoni.jpg');
            $pizza->setPrice(8.50);
            $pizza->setOkCeliacs(false);
            $mockPizzas[] = $pizza;

            $pizza = new Pizza();
            $pizza->setTitle('Barbacoa');
            $pizza->setImage('barbacoa.jpg');
            $pizza->setPrice(9.00);
            $pizza->setOkCeliacs(false);
            $mockPizzas[] = $pizza;

            foreach ($mockPizzas as $pizza) {
                $entityManager->persist($pizza);
            }

            $entityManager->flush();
        }
        
    }

    public function getAllPizzas(): array
    {
        $pizzas = $this->entityManager->getRepository(Pizza::class)->findAll();
        if (empty($pizzas)) {
            $this->logger->warning('No hay pizzas disponibles.');
            return [];
        }

        return array_map(function (Pizza $pizza) {
            return new PizzaDTO(
                id: $pizza->getId(),
                title: $pizza->getTitle(),
                image: $pizza->getImage(),
                price: $pizza->getPrice(),
                ingredients: array_map(function (Ingredient $ingredient) {
                    return new IngredientDTO(
                        name: $ingredient->getName()
                    );
                }, $pizza->getIngredients()->toArray())
            );
        }, $pizzas);
    }

}
