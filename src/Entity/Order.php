<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
{
    public function __construct()
    {
        $this->orderedItems = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $userMail;


    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $state;

    /**
     * @ORM\OneToOne(targetEntity="Cart")
     */
    private $cart;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $type;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedItems", mappedBy="order", cascade={"persist", "remove"})
     */
    private $orderedItems;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $address;

    /**
     * @return mixed $orderedItems
     */
    public function getOrderedItems()
    {
        return $this->orderedItems;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserName($userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserMail()
    {
        return $this->userMail;
    }

    public function setUserMail($userMail): self
    {
        $this->userMail = $userMail;

        return $this;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCart(): ?int
    {
        return $this->cart;
    }

    public function setCart($cartId): self
    {
        $this->cart = $cartId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
