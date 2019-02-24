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
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Shopcard", mappedBy="order", cascade={"persist", "remove"})
     * @var Collection
     */
    private $orderItems;
    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }
    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->orderItems;
    }
    public function addorderItems(Shopcard $shopcard): self
    {
        if (!$this->orderItems->contains($shopcard)) {
            $shopcard->setOrders($this);
            $this->orderItems[] = $shopcard;
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
    public function setUser($user): void
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
}
