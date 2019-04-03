<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\CartItemRepository")
 */
class CartItem
{
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Product", inversedBy="products")
     */
    private $product;

    /**
     * @Doctrine\ORM\Mapping\Column(type="decimal", scale=2)
     */
    private $productPrice;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Cart", inversedBy="cartItems")
     */
    private $cart;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert product quantity")
     */
    private $productQuantity;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
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
