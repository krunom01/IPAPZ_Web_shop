<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="products")
     */
    private $product;

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
     * @ORM\Column(type="integer")
     */
    private $userId;


    /**
     * CartItem constructor.
     */
    public function __construct()
    {
        $this->product = new ArrayCollection();
    }


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

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */

    public function setProduct($product): void
    {
        $this->product = $product;
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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
