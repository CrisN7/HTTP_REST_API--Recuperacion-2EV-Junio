<?php
namespace App\Model;


class OrderResponseDTO
{
    public function __construct(
        public int $id,
        public array $pizzas_order
    ) {}
}
