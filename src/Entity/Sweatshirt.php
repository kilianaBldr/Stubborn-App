<?php

namespace App\Entity;

use App\Repository\SweatshirtRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SweatshirtRepository::class)]
#[ApiResource]
#[Vich\Uploadable]
class Sweatshirt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stockXS = null;

    #[ORM\Column]
    private ?int $stockS = null;

    #[ORM\Column]
    private ?int $stockM = null;

    #[ORM\Column]
    private ?int $stockL = null;

    #[ORM\Column]
    private ?int $stockXL = null;

    #[ORM\Column(length: 255)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'sweatshirt_image', fileNameProperty: 'imageName')]
    #[Assert\Image(
        maxSize: "5M",
        mimeTypes: ["image/jpeg", "image/png"]
    )]
    private ?File $imageFile = null;

    #[ORM\Column]
    private ?bool $isFeatured = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStockXS(): ?int
    {
        return $this->stockXS;
    }

    public function setStockXS(int $stockXS): static
    {
        $this->stockXS = $stockXS;

        return $this;
    }

    public function getStockS(): ?int
    {
        return $this->stockS;
    }

    public function setStockS(int $stockS): static
    {
        $this->stockS = $stockS;

        return $this;
    }

    public function getStockM(): ?int
    {
        return $this->stockM;
    }

    public function setStockM(int $stockM): static
    {
        $this->stockM = $stockM;

        return $this;
    }

    public function getStockL(): ?int
    {
        return $this->stockL;
    }

    public function setStockL(int $stcokL): static
    {
        $this->stockL = $stockL;

        return $this;
    }

    public function getStockXL(): ?int
    {
        return $this->stockXL;
    }

    public function setStockXL(int $stockXL): static
    {
        $this->stockXL = $stockXL;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function setImageFile(?File $imageFile = null):void
    {
        $this->imageFile = $imageFile;
        if ($imageFile){
            $this->updateAt = new \DateTime;
        }
    }
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function isFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }
}
