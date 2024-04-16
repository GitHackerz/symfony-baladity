<?php

namespace App\Entity;

use App\Repository\TacheProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheProjetRepository::class)]
class TacheProjet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\ManyToOne(cascade: ['remove', 'persist'], inversedBy: 'tacheProjets')]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(cascade: ['remove', 'persist'], inversedBy: 'tacheProjets')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'tacheProjet', targetEntity: TacheCommentaires::class)]
    private Collection $tacheCommentaires;

    public function __construct()
    {
        $this->tacheCommentaires = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): static
    {
        $this->projet = $projet;

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
            $tacheCommentaire->setTacheProjet($this);
        }

        return $this;
    }

    public function removeTacheCommentaire(TacheCommentaires $tacheCommentaire): static
    {
        if ($this->tacheCommentaires->removeElement($tacheCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($tacheCommentaire->getTacheProjet() === $this) {
                $tacheCommentaire->setTacheProjet(null);
            }
        }

        return $this;
    }
}
