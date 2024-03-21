<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heureDebut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heureFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Citoyen $citoyen = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandeAssociation::class)]
    private Collection $demandeAssociations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandeDocument::class)]
    private Collection $demandeDocuments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TacheProjet::class)]
    private Collection $tacheProjets;

    public function __construct()
    {
        $this->demandeAssociations = new ArrayCollection();
        $this->demandeDocuments = new ArrayCollection();
        $this->tacheProjets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
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
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): static
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getHeureDebut(): ?string
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(string $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?string
    {
        return $this->heureFin;
    }

    public function setHeureFin(?string $heureFin): static
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCitoyen(): ?Citoyen
    {
        return $this->citoyen;
    }

    public function setCitoyen(?Citoyen $citoyen): static
    {
        $this->citoyen = $citoyen;

        return $this;
    }

    /**
     * @return Collection<int, DemandeAssociation>
     */
    public function getDemandeAssociations(): Collection
    {
        return $this->demandeAssociations;
    }

    public function addDemandeAssociation(DemandeAssociation $demandeAssociation): static
    {
        if (!$this->demandeAssociations->contains($demandeAssociation)) {
            $this->demandeAssociations->add($demandeAssociation);
            $demandeAssociation->setUser($this);
        }

        return $this;
    }

    public function removeDemandeAssociation(DemandeAssociation $demandeAssociation): static
    {
        if ($this->demandeAssociations->removeElement($demandeAssociation)) {
            // set the owning side to null (unless already changed)
            if ($demandeAssociation->getUser() === $this) {
                $demandeAssociation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeDocument>
     */
    public function getDemandeDocuments(): Collection
    {
        return $this->demandeDocuments;
    }

    public function addDemandeDocument(DemandeDocument $demandeDocument): static
    {
        if (!$this->demandeDocuments->contains($demandeDocument)) {
            $this->demandeDocuments->add($demandeDocument);
            $demandeDocument->setUser($this);
        }

        return $this;
    }

    public function removeDemandeDocument(DemandeDocument $demandeDocument): static
    {
        if ($this->demandeDocuments->removeElement($demandeDocument)) {
            // set the owning side to null (unless already changed)
            if ($demandeDocument->getUser() === $this) {
                $demandeDocument->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TacheProjet>
     */
    public function getTacheProjets(): Collection
    {
        return $this->tacheProjets;
    }

    public function addTacheProjet(TacheProjet $tacheProjet): static
    {
        if (!$this->tacheProjets->contains($tacheProjet)) {
            $this->tacheProjets->add($tacheProjet);
            $tacheProjet->setUser($this);
        }

        return $this;
    }

    public function removeTacheProjet(TacheProjet $tacheProjet): static
    {
        if ($this->tacheProjets->removeElement($tacheProjet)) {
            // set the owning side to null (unless already changed)
            if ($tacheProjet->getUser() === $this) {
                $tacheProjet->setUser(null);
            }
        }

        return $this;
    }
}