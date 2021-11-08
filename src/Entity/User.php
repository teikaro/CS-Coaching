<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="L'email que vous avez renseigner existe déjà.")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=180, unique=true)
     */
    #[Assert\NotBlank(
        message: 'Merci de renseigner une adresse Email !',
    )]
    #[Assert\Email(
        message: 'L\'adresse Email {{ value }} n\'est pas valide !',
    )]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Assert\Length(
        min: 8,
        max: 4096,
        minMessage: 'Votre mot de passe doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Mot de passe trop grand !',
    )]
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    #[Assert\NotBlank(
        message: 'Merci de renseigner un nom.',
    )]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut dépasser {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/[A-Za-z]/',
        message: 'Le nom ne peut contenir que des lettres.',
    )]
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    #[Assert\NotBlank(
        message: 'Merci de renseigner un prénom.',
    )]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le prénom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le prénom ne peut dépasser {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/[A-Za-z]/',
        message: 'Le prénom ne peut contenir que des lettres.',
    )]
    private $firstName;

    /**
     * Facultatif
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    // TODO : Note pour STAGE, max 15 caractères, considérer les numéros étrangers (min et max)
    // Le numéro est au format 10 chiffres actuellement
    #[Assert\Length(
        min: 10,
        max: 10,
        minMessage: 'Le numéro de téléphone doit contenir {{ 10 }} chiffres.',
        maxMessage: 'Le numéro de téléphone ne peut dépasser {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/[0-9]/',
        message: 'Le numéro de téléphone ne peut contenir que des chiffres.',
    )]
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $UpdatedAt;

    /**
     * Facultatif
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le nom de l\'entreprise ne peut dépasser {{ limit }} caractères.',
    )]
    private $corporateName;

    /**
     * @ORM\ManyToMany(targetEntity=SocialNetwork::class, inversedBy="users")
     */
    private $socialNetworks;

    /**
     * @ORM\OneToOne(targetEntity=Photo::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="user", orphanRemoval=true)
     */
    private $proposeServices;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="user", orphanRemoval=true)
     */
    private $presentProjects;

    public function __construct()
    {
        $this->socialNetworks = new ArrayCollection();
        $this->proposeServices = new ArrayCollection();
        $this->presentProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    /* Doctrine Lifecycle Listeners */
    /**
     * @ORM\PrePersist
     */
    public function setRegisteredAt(): void
    {
        $this->registeredAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    /* Doctrine Lifecycle Listeners */
    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): void
    {
        $this->UpdatedAt = new \DateTime();
    }

    public function getCorporateName(): ?string
    {
        return $this->corporateName;
    }

    public function setCorporateName(?string $corporateName): self
    {
        $this->corporateName = $corporateName;

        return $this;
    }

    /**
     * @return Collection|SocialNetwork[]
     */
    public function getSocialNetworks(): Collection
    {
        return $this->socialNetworks;
    }

    public function addSocialNetwork(SocialNetwork $socialNetwork): self
    {
        if (!$this->socialNetworks->contains($socialNetwork)) {
            $this->socialNetworks[] = $socialNetwork;
        }

        return $this;
    }

    public function removeSocialNetwork(SocialNetwork $socialNetwork): self
    {
        $this->socialNetworks->removeElement($socialNetwork);

        return $this;
    }

    public function getPhotos(): ?Photo
    {
        return $this->photos;
    }

    public function setPhotos(?Photo $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    /* méthodes pour remettre à zéro les rôles */
    public function razRole(): self
    {
        $this->roles = [];
        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getProposeServices(): Collection
    {
        return $this->proposeServices;
    }

    public function addProposeService(Service $proposeService): self
    {
        if (!$this->proposeServices->contains($proposeService)) {
            $this->proposeServices[] = $proposeService;
            $proposeService->setUser($this);
        }

        return $this;
    }

    public function removeProposeService(Service $proposeService): self
    {
        if ($this->proposeServices->removeElement($proposeService)) {
            // set the owning side to null (unless already changed)
            if ($proposeService->getUser() === $this) {
                $proposeService->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getPresentProjects(): Collection
    {
        return $this->presentProjects;
    }

    public function addPresentProject(Project $presentProject): self
    {
        if (!$this->presentProjects->contains($presentProject)) {
            $this->presentProjects[] = $presentProject;
            $presentProject->setUser($this);
        }

        return $this;
    }

    public function removePresentProject(Project $presentProject): self
    {
        if ($this->presentProjects->removeElement($presentProject)) {
            // set the owning side to null (unless already changed)
            if ($presentProject->getUser() === $this) {
                $presentProject->setUser(null);
            }
        }

        return $this;
    }
}
