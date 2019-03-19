<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderedItemsRepository")
 */
class OrderedItems
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ordereditems", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $user;
    /**
     * @ORM\Column(name="adress",type="string", length=100)
     * @Assert\NotBlank()
     */
    private $adress;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $paid;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Shopcard", mappedBy="ordereditems", cascade={"persist", "remove"})
     * @var Collection
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $userEmail;

    /**
     * @ORM\Column(type="integer", length=100)
     */
    private $totalPrice;



    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->date = new \DateTime();

    }
    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
    public function addorderItems(Shopcard $shopcard): self
    {
        if (!$this->items->contains($shopcard)) {
            $shopcard->setOrders($this);
            $this->items[] = $shopcard;
        }
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
    public function getAdress()
    {
        return $this->adress;
    }
    public function setAdress($adress): self
    {
        $this->adress = $adress;
        return $this;
    }
    public function getPaid()
    {
        return $this->paid;
    }
    public function setPaid($paid): self
    {
        $this->paid = $paid;
        return $this;
    }

    public function getPhone()
    {
        return $this->Phone;
    }

    public function setPhone($Phone): self
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function setUserEmail($userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }


}
