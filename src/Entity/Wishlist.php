<?php

namespace App\Entity;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\WishlistRepository")
 */
class Wishlist
{
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\User", inversedBy="wishList")
     */
    private $user;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Product", inversedBy="wishList")
     */
    private $product;

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

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product): self
    {
        $this->product = $product;

        return $this;
    }
}
