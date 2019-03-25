<?php

namespace App\Entity;



/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\OrderedItemsRepository")
 */
class OrderedItems
{
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderedItems")
     */
    private $order;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Product", inversedBy="products")
     */
    private $product;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $productPrice;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $productQuantity;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $userId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder($order): self
    {
        $this->order = $order;

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

    public function getProductPrice(): ?int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $productQuantity): self
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
