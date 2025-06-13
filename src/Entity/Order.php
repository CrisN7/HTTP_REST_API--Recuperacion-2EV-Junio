<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $delivery_time = null;

    #[ORM\Column(length: 255)]
    private ?string $delivery_address = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_type = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    /**
     * @var Collection<int, PizzaOrder>
     */
    #[ORM\OneToMany(targetEntity: PizzaOrder::class, mappedBy: 'orderRef')]
    private Collection $pizzaOrders;

    public function __construct()
    {
        $this->pizzaOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryTime(): ?string
    {
        return $this->delivery_time;
    }

    public function setDeliveryTime(string $delivery_time): static
    {
        $this->delivery_time = $delivery_time;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->delivery_address;
    }

    public function setDeliveryAddress(string $delivery_address): static
    {
        $this->delivery_address = $delivery_address;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    public function setPaymentType(string $payment_type): static
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, PizzaOrder>
     */
    public function getPizzaOrders(): Collection
    {
        return $this->pizzaOrders;
    }

    public function addPizzaOrder(PizzaOrder $pizzaOrder): static
    {
        if (!$this->pizzaOrders->contains($pizzaOrder)) {
            $this->pizzaOrders->add($pizzaOrder);
            $pizzaOrder->setOrderRef($this);
        }

        return $this;
    }

    public function removePizzaOrder(PizzaOrder $pizzaOrder): static
    {
        if ($this->pizzaOrders->removeElement($pizzaOrder)) {
            // set the owning side to null (unless already changed)
            if ($pizzaOrder->getOrderRef() === $this) {
                $pizzaOrder->setOrderRef(null);
            }
        }

        return $this;
    }
}
