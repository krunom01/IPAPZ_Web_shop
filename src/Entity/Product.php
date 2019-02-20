<?php

namespace App\Entity;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
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
}
