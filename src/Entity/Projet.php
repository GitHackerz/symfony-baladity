<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $titre;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $description;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateDebut;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateFin;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $statut;

    #[ORM\Column(type: 'float')]
    private ?float $budget;

    #[ORM\ManyToMany(targetEntity: Utilisateurs::class, inversedBy: 'projet')]
    #[ORM\JoinTable(name: 'utilisateurs_projet')]
    private $users = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateurs>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Utilisateurs $users): static
    {
        if (!$this->users->contains($users)) {
            $this->users->add($users);
        }

        return $this;
    }

    public function removeUser(Utilisateurs $users): static
    {
        $this->users->removeElement($users);

        return $this;
    }

}
