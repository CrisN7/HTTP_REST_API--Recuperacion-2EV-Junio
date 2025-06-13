<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class PizzaOrderDTO
{
    public function __construct(
        #[Assert\NotNull]
        public int $pizza_id,

        #[Assert\NotNull]
        #[Assert\Positive]
        public int $quantity
    ) {}
}
