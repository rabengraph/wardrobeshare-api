<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ClothingRepository")
 */
class Clothing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Mockaroo\Parameter({"type"="Product (Grocery)"});
     * @Groups("read")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PixabayImage")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("read")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clothings")
     */
    private $person;

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

    public function getImage(): ?PixabayImage
    {
        return $this->image;
    }

    public function setImage(?PixabayImage $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPerson(): ?User
    {
        return $this->person;
    }

    public function setPerson(?User $person): self
    {
        $this->person = $person;

        return $this;
    }
}
