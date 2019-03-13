<?php

namespace App\Entity;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Category;


/**
 * Class Product
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"productnumber"}, message="There is already an product with this productnumber")
 */
class Product
{
    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->shopcards = new ArrayCollection();
        $this->products = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCategory", mappedBy="product", cascade={"persist", "remove"})
     *
     */
    private $products;

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
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="upload image with .jpg or .jpeg!")
     * @Assert\File(mimeTypes={ "image/jpg", "image/jpeg" })
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

    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */

    public function setName($name)
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

    public function setProductnumber($productnumber)
    {
        $this->productnumber = $productnumber;

    }

    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */

    public function setPrice($price)
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

    public function setImage($image)
    {
        $this->image = $image;

    }
    /**
     * @param ArrayCollection $products
     */
    public function addProducts(ArrayCollection $products)
    {
        $this->products = $products;
    }
    /**
     * @param ProductCategory $products
     */
    public function setProducts(ProductCategory $products)
    {
        $this->products = $products;
    }

}
