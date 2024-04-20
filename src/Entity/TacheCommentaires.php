<?php

namespace App\Entity;

use App\Repository\TacheCommentairesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheCommentairesRepository::class)]
class TacheCommentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'tacheCommentaires')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'tacheCommentaires')]
    private ?TacheProjet $tacheProjet = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getTacheProjet(): ?TacheProjet
    {
        return $this->tacheProjet;
    }

    public function setTacheProjet(?TacheProjet $tacheProjet): static
    {
        $this->tacheProjet = $tacheProjet;

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
}
