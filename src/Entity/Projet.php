<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le titre doit contenir au moins 3 caractères', maxMessage: 'Le titre doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'Le titre doit être une chaîne de caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/', message: 'Le titre ne doit contenir que des lettres, des chiffres et des tirets')]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'La description doit contenir au moins 3 caractères', maxMessage: 'La description doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'La description doit être une chaîne de caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/', message: 'La description ne doit contenir que des lettres, des chiffres et des tirets')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le statut ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le statut doit contenir au moins 3 caractères', maxMessage: 'Le statut doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'Le statut doit être une chaîne de caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/', message: 'Le statut ne doit contenir que des lettres, des chiffres et des tirets')]
    #[Assert\Choice(choices: ['ACTIVE', 'INACTIVE'], message: 'Le statut doit être active ou inactive')]
    private ?string $statut = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le budget ne doit pas être vide')]
    #[Assert\Type('float', message: 'Le budget doit être un nombre')]
    #[Assert\Positive(message: 'Le budget doit être un nombre positif')]
    private ?float $budget = null;

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: TacheProjet::class)]
    private Collection $tacheProjets;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projets')]
    private Collection $user;

    #[ORM\Column(type: "date")]
    #[Assert\LessThanOrEqual(propertyPath: 'dateFin', message: 'La date de début doit être inférieure à la date de fin')]
    #[Assert\GreaterThanOrEqual(value: 'today', message: 'La date de début doit être supérieure ou égale à la date du jour')]
    private ?DateTimeInterface $dateDebut;

    #[ORM\Column(type: "date")]
    #[Assert\NotNull(message: 'La date de fin ne doit pas être vide')]
    #[Assert\GreaterThanOrEqual(propertyPath: 'dateDebut', message: 'La date de fin doit être supérieure à la date de début')]
    private ?DateTimeInterface $dateFin;

    public function __construct()
    {
        $this->tacheProjets = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->dateDebut = new DateTime();
        $this->dateFin = new DateTime();
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
            $tacheProjet->setProjet($this);
        }

        return $this;
    }

    public function removeTacheProjet(TacheProjet $tacheProjet): static
    {
        if ($this->tacheProjets->removeElement($tacheProjet)) {
            // set the owning side to null (unless already changed)
            if ($tacheProjet->getProjet() === $this) {
                $tacheProjet->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

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
}
