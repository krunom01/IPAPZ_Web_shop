<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
