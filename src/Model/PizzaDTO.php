<?php
namespace App\Model;

class PizzaDTO
{
    
    public bool $ok_celiacs;
    public function __construct(
        public int $id,
        public string $title,
        public string $image,
        public float $price,
        /** @var IngredientDTO[] */
        public array $ingredients,//de tipo IngredientDTO[]
    ){
        $this->ok_celiacs = $this->checkCeliacs();
    }

    private function checkCeliacs(): bool
    {
        // Lista de ingredientes no aptos para celiacos
        $nonCeliacIngredients = ['Pepperoni', 'Jamón York']; 

        foreach ($this->ingredients as $ingredient) {
            if (in_array($ingredient->name, $nonCeliacIngredients, true)) {
                return false; // Hay un ingrediente que no es apto
            }
        }
        return true; // Todos son aptos
    }

       
}
