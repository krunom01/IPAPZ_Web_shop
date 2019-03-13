<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class Category
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */

class Category
{

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */

    private $name;
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="categories", fetch="EAGER")
     * @ORM\JoinTable(name="ab_category_2_product",joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)},
     * inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)})
     */
    private $products;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }
    /**
     * @param ArrayCollection $products
     */
    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
    }
    public function __toString() {
        return $this->getName();
    }
    /**
     * @return int
     */

}
