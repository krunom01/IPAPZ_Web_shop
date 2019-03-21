<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CouponRepository")
 */
class Coupon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(
     *     pattern     = "/^[1-9][0-9]*$/",
     *     message     = "Numbers only")
     * @Assert\LessThan(100)
     * @Assert\GreaterThanOrEqual(10)
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedItems", mappedBy="coupon")
     */
    private $discount;

    /**
     * @ORM\Column(type="integer")
     */
    private $code;


    public function getId()
    {
        return $this->id;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }
}
