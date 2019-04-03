<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\OrderRepository")
 * @Doctrine\ORM\Mapping\Table(name="`order`")
 */
class Order
{
    public function __construct()
    {
        $this->orderedItems = new ArrayCollection();
        $this->date = new \DateTime();
    }
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=50)
     */
    private $userName;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=50)
     */
    private $userMail;


    /**
     * @Doctrine\ORM\Mapping\Column(type="decimal", scale=2)
     */
    private $totalPrice;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=50)
     */
    private $country;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=30)
     */
    private $type;
    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\OrderedItems",
     * mappedBy="order", cascade={"persist", "remove"})
     */
    private $orderedItems;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\User", inversedBy="userOrder")
     */
    private $user;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=50)
     */
    private $address;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     */
    private $date;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=20)
     */
    private $status;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer", nullable=true)
     */
    private $pdf;

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

    public function setTotalPrice($totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setState($country): self
    {
        $this->country = $country;

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

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUserId($user): self
    {
        $this->user = $user;

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

    public function getDate()
    {
        $newDate = $this->date->format('d-m-Y,H:i');
        return $newDate;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPdf(): ?int
    {
        return $this->pdf;
    }

    public function setPdf(?int $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

}
