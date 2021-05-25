<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=125)
     * @Assert\NotNull
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\Range(min=0, minMessage="The price must be greather or equal than {{ limit }}.")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true, length=4000)
     * @Assert\Length(max=4000, maxMessage="The description field cannot be longer than {{ limit }} characters.")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    private $stock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
