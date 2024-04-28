<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
#[UniqueEntity(fields:['nom' , 'prenom' , 'event','age'], message: 'Ce membre participe deja a cette evenement ')]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]*$/",
        message: "Le nom ne peut contenir que des lettres."
    )]
    #[Assert\NotBlank(message: "Le nom ne peut pas rester vide.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]*$/",
        message: "Le prénom ne peut contenir que des lettres."
    )]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    private ?string $prenom = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "L'âge ne peut pas être vide.")]
    #[Assert\GreaterThan(value: 17, message: "L'âge doit être supérieur à 18.")]
    private ?int $age = null;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    #[Assert\NotNull(message:"l'evenement est obligatoire")]
    private ?Evenement $event = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getEvent(): ?Evenement
    {
        return $this->event;
    }

    public function setEvent(?Evenement $event): static
    {
        $this->event = $event;

        return $this;
    }
}
