<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Mockaroo;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ApiFilter(SearchFilter::class, properties={"occasion": "exact"})
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"readClothing"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"readClothing"})
     * @Mockaroo\Parameter({"type"="Date", "min"="1/1/2015", "max"="12/31/2020", "format"="%F %X"});
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Occasion")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"readClothing"})
     */
    private $occasion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clothing", inversedBy="eventsWorn")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clothing;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOccasion(): ?Occasion
    {
        return $this->occasion;
    }

    public function setOccasion(?Occasion $occasion): self
    {
        $this->occasion = $occasion;

        return $this;
    }

    public function getClothing(): ?Clothing
    {
        return $this->clothing;
    }

    public function setClothing(?Clothing $clothing): self
    {
        $this->clothing = $clothing;

        return $this;
    }
}
