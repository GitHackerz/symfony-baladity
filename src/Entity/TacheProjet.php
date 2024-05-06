<?php

namespace App\Entity;

use App\Repository\TacheProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TacheProjetRepository::class)]
class TacheProjet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le titre doit contenir au moins 3 caractères', maxMessage: 'Le titre doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'Le titre doit être une chaîne de caractères')]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'La description doit contenir au moins 3 caractères', maxMessage: 'La description doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'La description doit être une chaîne de caractères')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'La date ne doit pas être vide')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le statut ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le statut doit contenir au moins 3 caractères', maxMessage: 'Le statut doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'Le statut doit être une chaîne de caractères')]
    #[Assert\Choice(choices: ['To Do', 'In Progress', 'Done'], message: 'Le statut doit être parmi les valeurs suivantes: To Do, In Progress, Done')]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'tacheProjets')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(inversedBy: 'tacheProjets')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
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
