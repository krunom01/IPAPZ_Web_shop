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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCategory", mappedBy="product", cascade={"persist","remove"})
     */
    private $productCategory;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern     = "/^[a-z ćčžđš A-Z-]+$/i",
     *     message     = "Letters only")
     * @Assert\NotBlank(message="insert name")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="insert product number")
     */
    private $productnumber;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank(message="insert product price")
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
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern     = "/^[a-z ćčžđš A-Z-]+$/i",
     *     message     = "Letters only")
     * @Assert\NotBlank(message="insert your custom url")
     */
    private $urlCustom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wishlist", mappedBy="product", cascade={"persist", "remove"})
     *
     */
    private $wishList;

    /**
     * @return Collection|Shopcard[]
     */
    public function getProducts()
    {
        return $this->shopcards;
    }
    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->shopcards = new ArrayCollection();
        $this->productCategory = new ArrayCollection();
        $this->wishList = new ArrayCollection();
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
     * @return ArrayCollection|Category[]
     */
    public function getProductCategory()
    {
        $categories = new ArrayCollection();
        foreach ($this->productCategory as $prod) {
            /**
             * @var ProductCategory $prod
             */
            $categories[] = $prod->getCategory();
        }
        return $categories;
    }
    /**
     * @param ArrayCollection|ProductCategory $productCategory
     */
    public function setProductCategory(ArrayCollection $productCategory)
    {
        foreach ($productCategory as $category) {
            /**
             * @var ProductCategory $newProductCategory
             */
            $newProductCategory = new ProductCategory();
            $newProductCategory->setProduct($this);
            $newProductCategory->setCategory($category);
            $this->productCategory[] = $newProductCategory;
        }
    }

    public function getUrlCustom()
    {
        return $this->urlCustom;
    }

    public function setUrlCustom($urlCustom): self
    {
        $this->urlCustom = $urlCustom;

        return $this;
    }
    /**
     * @return ArrayCollection|Wishlist[]
     *
     */
    public function getWishList()
    {
        return $this->wishList;
    }

    public function setWishList($wishList): self
    {
        $this->wishList = $wishList;

        return $this;
    }
}
