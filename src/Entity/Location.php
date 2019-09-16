<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Mockaroo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
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
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="City"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"readUser", "readClothing"})
     * @Mockaroo\Parameter({"type"="Country"})
     */
    private $country;

    /**
     * @ORM\Column(type="float")
     * @Mockaroo\Parameter({"type"="Latitude"})
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     * @Mockaroo\Parameter({"type"="Longitude"})
     */
    private $lng;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
