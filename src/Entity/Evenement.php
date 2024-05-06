<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[UniqueEntity(fields: ['titre','lieu','date','nomContact','emailContact'],message:'Cet evenement existe deja ')]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le titre ne peut pas être vide")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description ne peut pas être vide")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La date ne peut pas être vide")]
    private ?string $date = null;
 
    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern:"/^[a-zA-Z\s]*$/",
        message:"Le lieu ne peut contenir que des lettres et des espaces"
    )]
    #[Assert\NotBlank(message:"Le lieu ne peut pas être vide")]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern:"/^[a-zA-Z\s]*$/",
        message:"Le nom du contact ne peut contenir que des lettres et des espaces"
    )]
    #[Assert\NotBlank(message:"Le nom du contact ne peut pas être vide")]
    private ?string $nomContact = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'email du contact ne peut pas être vide")]
    #[Assert\Email(message:"L'email doit être valide")]
    private ?string $emailContact = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Le statut ne peut pas être vide")]
    private ?bool $statut = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Membre::class)]
    private Collection $membres;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'evenements')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $user;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
        $this->user = new ArrayCollection();
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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getNomContact(): ?string
    {
        return $this->nomContact;
    }

    public function setNomContact(string $nomContact): static
    {
        $this->nomContact = $nomContact;

        return $this;
    }

    public function getEmailContact(): ?string
    {
        return $this->emailContact;
    }

    public function setEmailContact(string $emailContact): static
    {
        $this->emailContact = $emailContact;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Membre>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Membre $membre): static
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
            $membre->setEvent($this);
        }

        return $this;
    }

    public function removeMembre(Membre $membre): static
    {
        if ($this->membres->removeElement($membre)) {
            // set the owning side to null (unless already changed)
            if ($membre->getEvent() === $this) {
                $membre->setEvent(null);
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
}
