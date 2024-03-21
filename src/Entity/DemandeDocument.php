<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DemandeDocumentRepository;

#[ORM\Entity(repositoryClass: DemandeDocumentRepository::class)]
class DemandeDocument
{
    #[ORM\Column(type: 'integer', nullable: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $idDdoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $typeDdoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $descriptionDdoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $statutDdoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $dateDdoc;

    #[ORM\Column(type: 'string', length: 255)]
    private string $dateTraitementDdoc = '\'N/A\'';

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $idClient;

    #[ORM\ManyToOne(targetEntity: Documents::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Documents $idDocument;

    public function getIdDdoc(): ?int
    {
        return $this->idDdoc;
    }

    public function getTypeDdoc(): ?string
    {
        return $this->typeDdoc;
    }

    public function setTypeDdoc(string $typeDdoc): static
    {
        $this->typeDdoc = $typeDdoc;

        return $this;
    }

    public function getDescriptionDdoc(): ?string
    {
        return $this->descriptionDdoc;
    }

    public function setDescriptionDdoc(string $descriptionDdoc): static
    {
        $this->descriptionDdoc = $descriptionDdoc;

        return $this;
    }

    public function getStatutDdoc(): ?string
    {
        return $this->statutDdoc;
    }

    public function setStatutDdoc(string $statutDdoc): static
    {
        $this->statutDdoc = $statutDdoc;

        return $this;
    }

    public function getDateDdoc(): ?string
    {
        return $this->dateDdoc;
    }

    public function setDateDdoc(string $dateDdoc): static
    {
        $this->dateDdoc = $dateDdoc;

        return $this;
    }

    public function getDateTraitementDdoc(): ?string
    {
        return $this->dateTraitementDdoc;
    }

    public function setDateTraitementDdoc(string $dateTraitementDdoc): static
    {
        $this->dateTraitementDdoc = $dateTraitementDdoc;

        return $this;
    }

    public function getIdClient(): ?Utilisateurs
    {
        return $this->idClient;
    }

    public function setIdClient(?Utilisateurs $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getIdDocument(): ?Documents
    {
        return $this->idDocument;
    }

    public function setIdDocument(?Documents $idDocument): static
    {
        $this->idDocument = $idDocument;

        return $this;
    }


}
