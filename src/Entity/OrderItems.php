<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemsRepository")
 */
class OrderItems
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderid;

    /**
     * @ORM\Column(type="integer")
     */
    private $productid;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberofproducts;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderid(): ?int
    {
        return $this->orderid;
    }

    public function setOrderid(int $orderid): self
    {
        $this->orderid = $orderid;

        return $this;
    }

    public function getProductid(): ?int
    {
        return $this->productid;
    }

    public function setProductid(int $productid): self
    {
        $this->productid = $productid;

        return $this;
    }

    public function getNumberofproducts(): ?int
    {
        return $this->numberofproducts;
    }

    public function setNumberofproducts(int $numberofproducts): self
    {
        $this->numberofproducts = $numberofproducts;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }
}
