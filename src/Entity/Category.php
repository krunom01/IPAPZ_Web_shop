<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Category
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{


    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=50)
     */

    private $name;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\ProductCategory",
     * mappedBy="category", cascade={"persist", "remove"})
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

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


    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */

    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}
