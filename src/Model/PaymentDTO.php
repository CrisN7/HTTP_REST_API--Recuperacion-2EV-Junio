<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $payment_type,

        #[Assert\NotBlank(message: 'El número de pedido no puede estar vacío.')]
        public string $number
    ) {}
}