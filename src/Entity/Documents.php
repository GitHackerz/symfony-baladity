<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentsRepository::class)]
class Documents
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $idDoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $typeDoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $statutDoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $dateEmissionDoc;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $dateExpirationDoc;

    #[ORM\Column(type: 'boolean')]
    private ?bool $estarchive;

    #[ORM\Column(type: 'integer')]
    private ?int $nbReq = 0;

    public function getIdDoc(): ?int
    {
        return $this->idDoc;
    }

    public function getTypeDoc(): ?string
    {
        return $this->typeDoc;
    }

    public function setTypeDoc(string $typeDoc): static
    {
        $this->typeDoc = $typeDoc;

        return $this;
    }

    public function getStatutDoc(): ?string
    {
        return $this->statutDoc;
    }

    public function setStatutDoc(string $statutDoc): static
    {
        $this->statutDoc = $statutDoc;

        return $this;
    }

    public function getDateEmissionDoc(): ?string
    {
        return $this->dateEmissionDoc;
    }

    public function setDateEmissionDoc(string $dateEmissionDoc): static
    {
        $this->dateEmissionDoc = $dateEmissionDoc;

        return $this;
    }

    public function getDateExpirationDoc(): ?string
    {
        return $this->dateExpirationDoc;
    }

    public function setDateExpirationDoc(string $dateExpirationDoc): static
    {
        $this->dateExpirationDoc = $dateExpirationDoc;

        return $this;
    }

    public function isEstarchive(): ?bool
    {
        return $this->estarchive;
    }

    public function setEstarchive(bool $estarchive): static
    {
        $this->estarchive = $estarchive;

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


}
