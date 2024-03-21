<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HistoriqueModificationRepository;

#[ORM\Entity(repositoryClass: HistoriqueModificationRepository::class)]
class HistoriqueModification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $description;

    #[ORM\Column(type: 'integer')]
    private ?int $idAssociation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getIdAssociation(): ?int
    {
        return $this->idAssociation;
    }

    public function setIdAssociation(int $idAssociation): static
    {
        $this->idAssociation = $idAssociation;

        return $this;
    }


}
