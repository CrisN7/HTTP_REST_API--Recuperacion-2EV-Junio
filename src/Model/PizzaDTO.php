<?php
namespace App\Model;

use PhpParser\Node\Expr\Cast\Double;

class PizzaDTO
{
    public function __construct(

        public int $id,
        public string $title,
        public string $image,
        public float $price,

        /** @var IngredientDTO[] */
        public array $ingredients,//de tipo IngredientDTO[]

    ){}
       
}
