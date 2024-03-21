<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReclamationRepository;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idreclamation;

    #[ORM\Column(type: 'string', length: 50)]
    private $typereclamation;

    #[ORM\Column(type: 'string', length: 255)]
    private $descriptionreclamation;

    #[ORM\Column(type: 'string', length: 50)]
    private $statutreclamation;

    #[ORM\Column(type: 'string', length: 50)]
    private $datereclamation;

    public function getIdreclamation(): ?int
    {
        return $this->idreclamation;
    }

    public function getTypereclamation(): ?string
    {
        return $this->typereclamation;
    }

    public function setTypereclamation(string $typereclamation): static
    {
        $this->typereclamation = $typereclamation;

        return $this;
    }

    public function getDescriptionreclamation(): ?string
    {
        return $this->descriptionreclamation;
    }

    public function setDescriptionreclamation(string $descriptionreclamation): static
    {
        $this->descriptionreclamation = $descriptionreclamation;

        return $this;
    }

    public function getStatutreclamation(): ?string
    {
        return $this->statutreclamation;
    }

    public function setStatutreclamation(string $statutreclamation): static
    {
        $this->statutreclamation = $statutreclamation;

        return $this;
    }

    public function getDatereclamation(): ?string
    {
        return $this->datereclamation;
    }

    public function setDatereclamation(string $datereclamation): static
    {
        $this->datereclamation = $datereclamation;

        return $this;
    }


}
