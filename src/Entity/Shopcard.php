<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class Shopcard
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ShopcardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Shopcard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="shopcards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }
    public function getProduct()
    {
        return $this->product;
    }
    public function setProduct(int $product): void
    {
        $this->product = $product;

    }
    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->user;
    }
    /**
     * @param mixed $user
     */
    public function setUserid(int $user): void
    {
        $this->product = $user;
    }

    public function __toString() {

    return $this->getUserid();
    }

}