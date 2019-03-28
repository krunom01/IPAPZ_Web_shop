<?php

namespace App\Entity;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\PaymentTypeRepository")
 * @Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity(fields={"type"},
 * message="There is already an payment method with this type")
 */
class PaymentType
{
    public function __construct()
    {
        $this->visibility = true;
    }
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=30)
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert payment type")
     */
    private $type;

    /**
     * @Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $visibility;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }
    public function __toString()
    {
        return $this->getType();
    }
}
