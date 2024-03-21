<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $dateEmission = null;

    #[ORM\Column(length: 255)]
    private ?string $dateExpiration = null;

    #[ORM\Column(length: 255)]
    private ?bool $estArchive = null;

    #[ORM\Column]
    private ?int $nbReq = null;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: DemandeDocument::class)]
    private Collection $demandeDocuments;

    public function __construct()
    {
        $this->demandeDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateEmission(): ?string
    {
        return $this->dateEmission;
    }

    public function setDateEmission(string $dateEmission): static
    {
        $this->dateEmission = $dateEmission;

        return $this;
    }

    public function getDateExpiration(): ?string
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(string $dateExpiration): static
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getEstArchive(): ?string
    {
        return $this->estArchive;
    }

    public function setEstArchive(string $estArchive): static
    {
        $this->estArchive = $estArchive;

        return $this;
    }

    public function getNbReq(): ?int
    {
        return $this->nbReq;
    }

    public function setNbReq(int $nbReq): static
    {
        $this->nbReq = $nbReq;

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
            $demandeDocument->setDocument($this);
        }

        return $this;
    }

    public function removeDemandeDocument(DemandeDocument $demandeDocument): static
    {
        if ($this->demandeDocuments->removeElement($demandeDocument)) {
            // set the owning side to null (unless already changed)
            if ($demandeDocument->getDocument() === $this) {
                $demandeDocument->setDocument(null);
            }
        }

        return $this;
    }
}
