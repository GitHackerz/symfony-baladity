<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?float $caisse = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: HistoriqueModification::class)]
    private Collection $historiqueModifications;

    public function __construct()
    {
        $this->historiqueModifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCaisse(): ?float
    {
        return $this->caisse;
    }

    public function setCaisse(float $caisse): static
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    /**
     * @return Collection<int, HistoriqueModification>
     */
    public function getHistoriqueModifications(): Collection
    {
        return $this->historiqueModifications;
    }

    public function addHistoriqueModification(HistoriqueModification $historiqueModification): static
    {
        if (!$this->historiqueModifications->contains($historiqueModification)) {
            $this->historiqueModifications->add($historiqueModification);
            $historiqueModification->setAssociation($this);
        }

        return $this;
    }

    public function removeHistoriqueModification(HistoriqueModification $historiqueModification): static
    {
        if ($this->historiqueModifications->removeElement($historiqueModification)) {
            // set the owning side to null (unless already changed)
            if ($historiqueModification->getAssociation() === $this) {
                $historiqueModification->setAssociation(null);
            }
        }

        return $this;
    }
}