<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class OrderNewDTO
{
    
    public function __construct(
    /** @var PizzaOrderDTO[] */
    // #[Assert\NotBlank]
    // #[Assert\Count(min: 1)]
    public array $pizzas_order, // array de PizzaOrderDTO

    public string $delivery_time,

    public string $delivery_address,

    #[Assert\Valid]
    public PaymentDTO $payment){}

    
       
}
