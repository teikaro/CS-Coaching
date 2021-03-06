<?php

namespace App\Entity;

use App\Repository\ResumeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ResumeRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Resume
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    #[Assert\NotBlank(
        message: 'Merci de renseigner un titre.',
    )]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le titre doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le titre ne peut dépasser {{ limit }} caractères.',
    )]
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     */

    //NotBlank sert à mettre un message si le champ est vide au moment de l'envoi

    #[Assert\NotBlank(
        message: 'Merci de renseigner un contenu.',
    )]

    //Lenght sert à définir des paramètres maximum/minimum et envoyer un message si le champ ne correspond pas à ces paramètres lors de l'envoi
    
    #[Assert\Length(
        min: 2,
        max: 50000,
        minMessage: 'Le contenu doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le contenu ne peut dépasser {{ limit }} caractères.',
    )]
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    #[Assert\NotBlank(
        message: 'Merci de renseigner un secteur d\'activité.',
    )]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Les précisions pour Claire Saurel doivent contenir au moins {{ limit }} caractères.',
        maxMessage: 'Les précisions pour Claire Saurel ne peuvent dépasser {{ limit }} caractères.',
    )]
    private ?string $sector;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private ?string $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="proposeArticle")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /* Doctrine Lifecycle Listeners */
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /* Doctrine Lifecycle Listeners */
    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): void
    {
        $this->UpdatedAt = new \DateTime();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
