<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements PasswordAuthenticatedUserInterface, userInterface

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'adresse email ne doit pas être vide.")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas une adresse email valide.")]
    private ?string $email = null;



    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le password ne doit pas être vide.")]
    private ?string $password = null;


    #[ORM\Column(type: 'string', length: 8)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne doit pas être vide.")]
    #[Assert\Regex(
        pattern: "/^\d{8}$/",
        message: "Le numéro de téléphone doit contenir exactement 8 chiffres."
    )]
    private ?int $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heureDebut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heureFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Citoyen::class, inversedBy: 'users')]
    private ?Citoyen $citoyen = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandeAssociation::class)]
    private Collection $demandeAssociations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandeDocument::class)]
    private Collection $demandeDocuments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TacheProjet::class, cascade: ['remove', 'persist'])]
    private Collection $tacheProjets;

    #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'user', cascade: ['remove', 'persist'])]
    private Collection $projets;

    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'user')]
    private Collection $evenements;
  
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TacheCommentaires::class)]
    private Collection $tacheCommentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Association::class)]
    private Collection $associations;

    public function __construct()
    {
        $this->demandeAssociations = new ArrayCollection();
        $this->demandeDocuments = new ArrayCollection();
        $this->tacheProjets = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->tacheCommentaires = new ArrayCollection();
        $this->associations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getRoles(): array
    {
        return [$this->role]; // Vous pouvez ajuster cette méthode selon votre logique de gestion des rôles
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


    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

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

    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->addUser($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            $projet->removeUser($this);
        }

        return $this;
    }
  
      /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->addUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TacheCommentaires>
     */
    public function getTacheCommentaires(): Collection
    {
        return $this->tacheCommentaires;
    }

    public function addTacheCommentaire(TacheCommentaires $tacheCommentaire): static
    {
        if (!$this->tacheCommentaires->contains($tacheCommentaire)) {
            $this->tacheCommentaires->add($tacheCommentaire);
            $tacheCommentaire->setUser($this);
        }

        return $this;
    }
  
    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeUser($this);
         }

        return $this;
    }

    public function removeTacheCommentaire(TacheCommentaires $tacheCommentaire): static
    {
        if ($this->tacheCommentaires->removeElement($tacheCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($tacheCommentaire->getUser() === $this) {
                $tacheCommentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Association>
     */
    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    public function addAssociation(Association $association): static
    {
        if (!$this->associations->contains($association)) {
            $this->associations->add($association);
            $association->setUser($this);
        }

        return $this;
    }

    public function removeAssociation(Association $association): static
    {
        if ($this->associations->removeElement($association)) {
            // set the owning side to null (unless already changed)
            if ($association->getUser() === $this) {
                $association->setUser(null);
            }
        }

        return $this;
    }

    public function getManagedProjects(): Collection
    {
        return $this->managedProjects;
    }

    public function addManagedProject(Projet $managedProject): static
    {
        if (!$this->managedProjects->contains($managedProject)) {
            $this->managedProjects->add($managedProject);
            $managedProject->setManager($this);
        }

        return $this;
    }

    public function removeManagedProject(Projet $managedProject): static
    {
        if ($this->managedProjects->removeElement($managedProject)) {
            // set the owning side to null (unless already changed)
            if ($managedProject->getManager() === $this) {
                $managedProject->setManager(null);
            }
        }

        return $this;
    }
}
