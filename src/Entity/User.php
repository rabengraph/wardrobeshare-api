<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_users")
 * @ApiResource(
 *      normalizationContext={"groups"={"readUser"}},
 *      collectionOperations={"get"},
 *      itemOperations={"get"}
 * )
 * @ApiFilter(SearchFilter::class, properties={"location": "exact"})
 * @ApiFilter(RangeFilter::class, properties={"rating", "location.lng", "location.lat"})
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Mockaroo\Parameter({"type"="Row Number"})
     * @Groups({"readUser", "readClothing"})

     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Mockaroo\Parameter({"type"="First Name (Female)"});
     * @Groups({"readUser", "readClothing"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Mockaroo\Parameter({"type"="Email Address"});
     * @Groups({"readUser"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PixabayImage")
     * @Groups({"readUser", "readClothing"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Clothing", mappedBy="person")
     * @Groups({"readUser"})
     */
    private $clothings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"readUser", "readClothing"})
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"readUser"})
     * @Mockaroo\Parameter({"type"="Slogan"})
     */
    private $slogan;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Number", "min" = 0, "max" = 5, "decimals" = 0});
     */
    private $rating;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="person")
     */
    private $events;

    public function __construct()
    {
        $this->clothings = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?PixabayImage
    {
        return $this->avatar;
    }

    public function setAvatar(?PixabayImage $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Clothing[]
     */
    public function getClothings(): Collection
    {
        return $this->clothings;
    }

    public function addClothing(Clothing $clothing): self
    {
        if (!$this->clothings->contains($clothing)) {
            $this->clothings[] = $clothing;
            $clothing->setPerson($this);
        }

        return $this;
    }

    public function removeClothing(Clothing $clothing): self
    {
        if ($this->clothings->contains($clothing)) {
            $this->clothings->removeElement($clothing);
            // set the owning side to null (unless already changed)
            if ($clothing->getPerson() === $this) {
                $clothing->setPerson(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setPerson($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getPerson() === $this) {
                $event->setPerson(null);
            }
        }

        return $this;
    }

}
