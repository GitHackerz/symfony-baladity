<?php

namespace App\Entity;

use App\Repository\DemandeAssociationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: DemandeAssociationRepository::class)]
class DemandeAssociation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Le nom de l'association ne peut pas être vide.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "L'adresse de l'association ne peut pas être vide.")]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le montant de la caisse ne peut pas être vide.")]
    #[Assert\PositiveOrZero(message: "Le montant de la caisse ne peut pas être négatif.")]
    private ?float $caisse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Le type de l'association ne peut pas être vide.")]
    private ?string $type = null;

    #[ORM\ManyToOne( inversedBy: 'demandeAssociations')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?User $user = null;

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
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->caisse !== null && $this->caisse < 0) {
            $context->buildViolation("Le montant de la caisse ne peut pas être négatif.")
                ->atPath('caisse')
                ->addViolation();
        }
    }
}
