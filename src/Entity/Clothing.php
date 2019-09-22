<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"readClothing"}},
 *      collectionOperations={"get"},
 *      itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ClothingRepository")
 * @ApiFilter(RangeFilter::class, properties={"price", "bust", "waist", "hips", "person.rating", "person.location.lat", "person.location.lng"})
 * @ApiFilter(SearchFilter::class, properties={"location": "exact", "size": "exact", "colors": "exact", "manufacturer": "exact", "eventsWorn.occasion": "exact", "culture": "exact"})
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
     * @Groups({"readUser", "readClothing"})
     */
    private $manufacturer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="clothing", orphanRemoval=true)
     * @Groups({"readClothing"})
     */
    private $eventsWorn;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Culture")
     * @Groups({"readClothing"})
     */
    private $cultures;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
        $this->eventsWorn = new ArrayCollection();
        $this->cultures = new ArrayCollection();
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

    /**
     * @return Collection|Event[]
     */
    public function getEventsWorn(): Collection
    {
        return $this->eventsWorn;
    }

    public function addEventsWorn(Event $eventsWorn): self
    {
        if (!$this->eventsWorn->contains($eventsWorn)) {
            $this->eventsWorn[] = $eventsWorn;
            $eventsWorn->setClothing($this);
        }

        return $this;
    }

    public function removeEventsWorn(Event $eventsWorn): self
    {
        if ($this->eventsWorn->contains($eventsWorn)) {
            $this->eventsWorn->removeElement($eventsWorn);
            // set the owning side to null (unless already changed)
            if ($eventsWorn->getClothing() === $this) {
                $eventsWorn->setClothing(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Culture[]
     */
    public function getCultures(): Collection
    {
        return $this->cultures;
    }

    public function addCulture(Culture $culture): self
    {
        if (!$this->cultures->contains($culture)) {
            $this->cultures[] = $culture;
        }

        return $this;
    }

    public function removeCulture(Culture $culture): self
    {
        if ($this->cultures->contains($culture)) {
            $this->cultures->removeElement($culture);
        }

        return $this;
    }
}
