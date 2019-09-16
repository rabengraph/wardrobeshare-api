<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(normalizationContext={"groups"={"readClothing"}})
 * @ORM\Entity(repositoryClass="App\Repository\ClothingRepository")
 */
class Clothing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"readUser", "readClothing"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Mockaroo\Parameter({"type"="Product (Grocery)"});
     * @Groups({"readUser", "readClothing"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PixabayImage")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"readUser", "readClothing"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clothings")
     * @Groups({"readClothing"})
     */
    private $person;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Custom List", "values"={"XXL", "XL", "L", "M", "S", "XS", "XXS"}})
     */
    private $size;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Number", "min"=29, "max"=45, "decimals"=0})
     */
    private $bust;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Number", "min"=29, "max"=45, "decimals"=0})
     */
    private $waist;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Number", "min"=29, "max"=45, "decimals"=0})
     */
    private $hips;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Number", "min"=20, "max"=200, "decimals"=0})
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Paragraphs", "min"=1, "max"=3})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Color")
     * @Groups({"readUser", "readClothing"})
     */
    private $colors;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manufacturer;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
    }

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

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getBust(): ?int
    {
        return $this->bust;
    }

    public function setBust(?int $bust): self
    {
        $this->bust = $bust;

        return $this;
    }

    public function getWaist(): ?int
    {
        return $this->waist;
    }

    public function setWaist(?int $waist): self
    {
        $this->waist = $waist;

        return $this;
    }

    public function getHips(): ?int
    {
        return $this->hips;
    }

    public function setHips(?int $hips): self
    {
        $this->hips = $hips;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Color[]
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors[] = $color;
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        if ($this->colors->contains($color)) {
            $this->colors->removeElement($color);
        }

        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}
