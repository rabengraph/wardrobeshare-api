<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PixabayImageRepository")
 */
class PixabayImage
{

    const TYPE_DRESS = 1;
    const TYPE_PROFILE = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("read")
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("read")
     */
    private $previewUrl;

    /**
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $previewWidth;

    /**
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $previewHeight;

    /**
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPreviewUrl(): ?string
    {
        return $this->previewUrl;
    }

    public function setPreviewUrl(string $previewUrl): self
    {
        $this->previewUrl = $previewUrl;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getPreviewWidth(): ?int
    {
        return $this->previewWidth;
    }

    public function setPreviewWidth(int $previewWidth): self
    {
        $this->previewWidth = $previewWidth;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getPreviewHeight(): ?int
    {
        return $this->previewHeight;
    }

    public function setPreviewHeight(int $previewHeight): self
    {
        $this->previewHeight = $previewHeight;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
