<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductCategoryRepository")
 * Class ProductCategory
 * @package App\Entity
 */
class ProductCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="categories")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productCategory")
     */
    private $product;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @param mixed $category
     */

    public function setCategory($category): void
    {
        $this->category = $category;

    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product): void
    {
        $this->product = $product;

    }
}
