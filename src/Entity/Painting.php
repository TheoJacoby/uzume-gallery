<?php

namespace App\Entity;

use App\Repository\PaintingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File as AssertFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PaintingRepository::class)]
#[Vich\Uploadable]
class Painting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $created = null;

    #[ORM\Column]
    #[Assert\Positive(message: 'La hauteur doit être positive')]
    private ?float $height = null;

    #[ORM\Column]
    #[Assert\Positive(message: 'La largeur doit être positive')]
    private ?float $width = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'paintings', fileNameProperty: 'imageName')]
    #[AssertFile(
        maxSize: '10M',
        maxSizeMessage: 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximale autorisée est {{ limit }} {{ suffix }}.',
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/png'],
        mimeTypesMessage: 'Le fichier doit être au format JPG ou PNG.'
    )]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'paintings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'paintings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Technique::class, inversedBy: 'paintings')]
    private Collection $techniques;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'painting', orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->techniques = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->created = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(\DateTimeImmutable $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Technique>
     */
    public function getTechniques(): Collection
    {
        return $this->techniques;
    }

    public function addTechnique(Technique $technique): static
    {
        if (!$this->techniques->contains($technique)) {
            $this->techniques->add($technique);
        }

        return $this;
    }

    public function removeTechnique(Technique $technique): static
    {
        $this->techniques->removeElement($technique);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPainting($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPainting() === $this) {
                $comment->setPainting(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}

