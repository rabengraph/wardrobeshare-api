<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"readManufacturer"}},
 *      collectionOperations={"get"},
 *      itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ManufacturerRepository")
 */
class Manufacturer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"readUser", "readClothing", "readManufacturer"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"readUser", "readClothing", "readManufacturer"})
     * @Mockaroo\Parameter({"type"="Company Name"})
     */
    private $name;

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
}
