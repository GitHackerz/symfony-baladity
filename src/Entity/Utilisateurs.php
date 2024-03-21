<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password;

    #[ORM\Column(type: 'integer')]
    private ?int $numtel;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $role;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private string $heuredebut = 'NULL';

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private string $heurefin = 'NULL';

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $image = 'NULL';

    #[ORM\ManyToOne(targetEntity: Citoyen::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Citoyen $idCitoyen;

    #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'user')]
    private $projet = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projet = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getHeuredebut(): ?string
    {
        return $this->heuredebut;
    }

    public function setHeuredebut(?string $heuredebut): static
    {
        $this->heuredebut = $heuredebut;

        return $this;
    }

    public function getHeurefin(): ?string
    {
        return $this->heurefin;
    }

    public function setHeurefin(?string $heurefin): static
    {
        $this->heurefin = $heurefin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getIdcitoyen(): ?Citoyen
    {
        return $this->idcitoyen;
    }

    public function setIdcitoyen(?Citoyen $idCitoyen): static
    {
        $this->idcitoyen = $idCitoyen;

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjet(): Collection
    {
        return $this->projet;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projet->contains($projet)) {
            $this->projet->add($projet);
            $projet->addUser($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projet->removeElement($projet)) {
            $projet->removeUser($this);
        }

        return $this;
    }

}
