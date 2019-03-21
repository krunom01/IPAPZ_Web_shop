<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Shopcard
 *
 * @package                                                         App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ShopcardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Shopcard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="shopcards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="shopcards" )
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderedItems", inversedBy="items")
     */
    private $ordereditems;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $productnumber;

    public function getId()
    {
        return $this->id;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;
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

    public function getProductnumber()
    {
        return $this->productnumber;
    }

    public function setProductnumber($productnumber): self
    {
        $this->productnumber = $productnumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->ordereditems;
    }

    /**
     * @param mixed $ordereditems
     */
    public function setOrders($ordereditems): void
    {
        $this->ordereditems = $ordereditems;
    }
}
