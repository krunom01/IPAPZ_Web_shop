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
     * @ORM\Column(type="integer")
     */
    private $coupon;


    /**
     * @ORM\OneToOne(targetEntity="Order")
     *
     */
    private $order;

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

    public function getCoupon(): ?int
    {
        return $this->coupon;
    }

    public function setCoupon(int $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @param mixed $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }
}
