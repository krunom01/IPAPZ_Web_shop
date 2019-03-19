<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomPageRepository")
 */
class CustomPage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="insert your content")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * @Assert\Regex(
     *     pattern     = "/^[a-z ćčžđš A-Z-]+$/i",
     *     message     = "Letters only")
     * @Assert\NotBlank(message="insert your custom url")
     */
    private $customUrl;

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCustomUrl()
    {
        return $this->customUrl;
    }

    public function setCustomUrl($customUrl): self
    {
        $this->customUrl = $customUrl;

        return $this;
    }
}
