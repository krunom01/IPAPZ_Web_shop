<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $subTotal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CartItem", mappedBy="cart", cascade={"persist", "remove"})
     */
    private $cartItems;

    /**
     * @return ArrayCollection|CartItem[]
     */

    public function getCartItems()
    {
        $categories = new ArrayCollection();
        foreach ($this->cartItems as $prod) {
            /**
             * @var CartItem $prod
             */
            $categories[] = $prod->getProductPrice();
        }
        return $categories;
    }

    public function setCategories($cartItems): self
    {
        $this->cartItems = $cartItems;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getSubTotal()
    {
        return $this->subTotal;
    }

    public function setSubTotal($subTotal): self
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total): self
    {
        $this->total = $total;

        return $this;
    }
}
