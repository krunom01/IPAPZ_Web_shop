<?php

namespace App\Entity;



/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\CountryShippingRepository")
 * @Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity(fields={"country"},
 * message="Country already exists!")
 * @Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity(fields={"countryCode"},
 * message="Country code already exists!")
 */
class CountryShipping
{

    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=40)
     * @Symfony\Component\Validator\Constraints\Regex(
     *     pattern     = "/^[a-z ćčžđš A-Z-]+$/i",
     *     message     = "Letters only"))
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert country")
     */
    private $country;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=3)
     */
    private $countryCode;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer", nullable=true)
     */
    private $shippingPrice;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getShippingPrice(): ?int
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice(int $shippingPrice): self
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }
    public function __toString()
    {
        return $this->getCountry();
    }
}
