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
            // $ingredient->setPizza(null);
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Mozzarella');
            // $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Pepperoni');
            // $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Jamón York');
            // $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Aceitunas');
            // $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Cebolla');
            // $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Champiñones');
            // $ingredient->setPizza(null); 
            $mockIngredients[] = $ingredient;

            $ingredient = new Ingredient();
            $ingredient->setName('Pimiento Verde');
            // $ingredient->setPizza(null); 
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
            $mockPizzas[] = $pizza;

            $pizza = new Pizza();
            $pizza->setTitle('Cuatro Quesos');
            $pizza->setImage('cuatro_quesos.jpg');
            $pizza->setPrice(8.00);
            $mockPizzas[] = $pizza;

            $pizza = new Pizza();
            $pizza->setTitle('Pepperoni');
            $pizza->setImage('pepperoni.jpg');
            $pizza->setPrice(8.50);
            $mockPizzas[] = $pizza;

            $pizza = new Pizza();
            $pizza->setTitle('Barbacoa');
            $pizza->setImage('barbacoa.jpg');
            $pizza->setPrice(9.00);
            $mockPizzas[] = $pizza;

            foreach ($mockPizzas as $pizza) {
                $entityManager->persist($pizza);
            }
        }

        $this->entityManager->flush();
        $pizzaRepo = $this->entityManager->getRepository(Pizza::class);
        $ingredientRepo = $this->entityManager->getRepository(Ingredient::class);

        // Obtener todas las pizzas e ingredientes
        $pizzas = $pizzaRepo->findAll();
        $ingredientsByName = [];
        foreach ($ingredientRepo->findAll() as $ing) {
            $ingredientsByName[$ing->getName()] = $ing;
        }

        // Asignar ingredientes a cada pizza por nombre
        foreach ($pizzas as $pizza) {
            switch ($pizza->getTitle()) {
                case 'Margarita':
                    $ingredientsByName['Tomate']->setPizza($pizza);
                    $ingredientsByName['Mozzarella']->setPizza($pizza);
                    break;

                case 'Cuatro Quesos':
                    $ingredientsByName['Mozzarella']->setPizza($pizza);
                    $ingredientsByName['Cebolla']->setPizza($pizza);
                    $ingredientsByName['Pimiento Verde']->setPizza($pizza);
                    break;

                case 'Pepperoni':
                    $ingredientsByName['Pepperoni']->setPizza($pizza);
                    $ingredientsByName['Tomate']->setPizza($pizza);
                    break;

                case 'Barbacoa':
                    $ingredientsByName['Jamón York']->setPizza($pizza);
                    $ingredientsByName['Cebolla']->setPizza($pizza);
                    $ingredientsByName['Aceitunas']->setPizza($pizza);
                    break;
            }
        }

        // Guardar cambios en base de datos
        $this->entityManager->flush();
        
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
                }, $pizza->getIngredients()->toArray()),
            );
        }, $pizzas);
    }

    

}
