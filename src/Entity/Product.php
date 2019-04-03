<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Product
 *
 * @package                                                        App\Entity
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Doctrine\ORM\Mapping\HasLifecycleCallbacks()
 * @Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity(fields={"productnumber"},
 * message="There is already an product with this productnumber")
 */
class Product
{

    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string",       length=50)
     * @Symfony\Component\Validator\Constraints\Regex(
     *     pattern     = "/^[a-z ćčžđš A-Z-]+$/i",
     *     message     = "Letters only")
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert name")
     */
    private $name;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert product number")
     */
    private $productnumber;

    /**
     * @Doctrine\ORM\Mapping\Column(type="decimal", scale=2)
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert product price")
     */
    private $price;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string",       length=255)
     * @Symfony\Component\Validator\Constraints\NotBlank(message="upload image with .jpg or .jpeg!")
     * @Symfony\Component\Validator\Constraints\File(mimeTypes={         "image/jpg", "image/jpeg" })
     */
    private $image;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string",       length=50)
     * @Symfony\Component\Validator\Constraints\Regex(
     *     pattern     = "/^[a-z ćčžđš A-Z-]+$/i",
     *     message     = "Letters only")
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert your custom url")
     */
    private $urlCustom;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Wishlist",
     * mappedBy="product", cascade={"persist", "remove"})
     */
    private $wishList;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\ProductCategory",
     * mappedBy="product", cascade={"persist","remove"})
     */
    private $productCategory;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\CartItem",
     * mappedBy="product", cascade={"persist", "remove"})
     */
    private $products;
    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\OrderedItems",
     * mappedBy="product", cascade={"persist", "remove"})
     */
    private $orderProducts;


    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->productCategory = new ArrayCollection();
        $this->wishList = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
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
        } return $categories;
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
    /**
     * @return ArrayCollection|Cart[]
     */
    public function getProduct()
    {
        return $this->products;
    }

    public function setProduct($products): self
    {
        $this->products = $products;

        return $this;
    }
}
