<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class IngredientDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public ?string $name = null,
    ){}
       
}
