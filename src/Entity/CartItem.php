<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartItemRepository")
 */
class CartItem
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
    private $productId;

    /**
     * @ORM\Column(type="integer")
     */
    private $productPrice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cart", inversedBy="cartItems")
     */
    private $cart;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="insert product quantity")
     */
    private $productQuantity;



    /**
     * @return mixed
     */
    public function getCart()
    {
        return $this->cart;
    }

    public function setCart($cart): self
    {
        $this->cart = $cart;

        return $this;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getProductPrice()
    {
        return $this->productPrice;
    }

    public function setProductPrice($productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductQuantity()
    {
        return $this->productQuantity;
    }

    public function setProductQuantity($productQuantity): self
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }
}
