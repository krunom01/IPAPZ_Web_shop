<?php

namespace App\Entity;


/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\CustomPageRepository")
 * @Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity(fields={"customUrl"},
 * message="There is already an page with this URL")
 */
class CustomPage
{
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="text")
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert your content")
     */
    private $content;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string",       length=30)
     * @Symfony\Component\Validator\Constraints\Regex(
     *     pattern     = "/^[a-z ćčžđš A-Z-]+$/i",
     *     message     = "Letters only")
     * @Symfony\Component\Validator\Constraints\NotBlank(message="insert your custom url")
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
