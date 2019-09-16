<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(normalizationContext={"groups"={"readColor"}})
 * @ORM\Entity(repositoryClass="App\Repository\ColorRepository")
 */
class Color
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"readUser", "readClothing", "readColor"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"readUser", "readClothing", "readColor"})
     * @Mockaroo\Parameter({"type"="Color"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"readUser", "readClothing", "readColor"})
     * @Mockaroo\Parameter({"type"="Hex Color"})
     */
    private $hex;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHex(): ?string
    {
        return $this->hex;
    }

    public function setHex(string $hex): self
    {
        $this->hex = $hex;

        return $this;
    }
}
