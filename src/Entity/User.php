<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_users")
 * @ApiResource(normalizationContext={"groups"={"read"}})
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Mockaroo\Parameter({"type"="Row Number"})
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Mockaroo\Parameter({"type"="First Name (Female)"});
     * @Groups("read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Mockaroo\Parameter({"type"="Email Address"});
     * @Groups("read")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PixabayImage")
     * @Groups("read")
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Clothing", mappedBy="person")
     * @Groups("read")
     */
    private $clothings;

    public function __construct()
    {
        $this->clothings = new ArrayCollection();
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
}
