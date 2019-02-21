<?php

namespace App\Entity;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Product
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->shopcards = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $productnumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;
    /**
     * @ORM\Column(type="integer")
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Shopcard", mappedBy="product", cascade={"persist", "remove"})
     *
     */
    private $shopcards;
    /**
     * @return Collection|Shopcard[]
     */
    public function getProducts()
    {
        return $this->shopcards;
    }

    /**
     * @return int
     */

    public function getId()
    {
        return $this->id;
    }

    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @param mixed $category
     */
    public function setCategory(int $category)
    {
        $this->category = $category;

    }

    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */

    public function setName(string $name)
    {
        $this->name = $name;

    }

    public function getProductnumber()
    {
        return $this->productnumber;
    }
    /**
     * @param mixed $productnumber
     */

    public function setProductnumber(int $productnumber)
    {
        $this->productnumber = $productnumber;

    }

    public function getPrice()
    {
        return $this->price;
    }
    /**
     * @param mixed $sku
     */

    public function setSku(int $sku)
    {
        $this->sku = $sku;

    }
    public function getSku()
    {
        return $this->sku;
    }
    /**
     * @param mixed $price
     */

    public function setPrice(int $price)
    {
        $this->price = $price;

    }

    public function getImage()
    {
        return $this->image;
    }
    /**
     * @param mixed $image
     */

    public function setImage(string $image)
    {
        $this->image = $image;

    }
    public function __toString()
    {
        return (string) $this->getId();
    }
}
