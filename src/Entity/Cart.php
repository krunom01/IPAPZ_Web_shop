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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userCart")
     */
    private $user;

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
     * @ORM\Column(type="integer")
     */
    private $coupon;


    /**
     * @return ArrayCollection|CartItem[]
     */
    public function getCartItems()
    {
        $cartItems = new ArrayCollection();
        foreach ($this->cartItems as $prod) {
            /**
             * @var CartItem $prod
             */
            $cartItems[] = $prod->getProduct();
        }
        return $cartItems;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

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

    public function getCoupon(): ?int
    {
        return $this->coupon;
    }

    public function setCoupon(int $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }

}
