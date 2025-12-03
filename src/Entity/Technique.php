<?php

namespace App\Entity;

use App\Repository\TechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechniqueRepository::class)]
class Technique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Painting::class, mappedBy: 'techniques')]
    private Collection $paintings;

    public function __construct()
    {
        $this->paintings = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Painting>
     */
    public function getPaintings(): Collection
    {
        return $this->paintings;
    }

    public function addPainting(Painting $painting): static
    {
        if (!$this->paintings->contains($painting)) {
            $this->paintings->add($painting);
            $painting->addTechnique($this);
        }

        return $this;
    }

    public function removePainting(Painting $painting): static
    {
        if ($this->paintings->removeElement($painting)) {
            $painting->removeTechnique($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}



