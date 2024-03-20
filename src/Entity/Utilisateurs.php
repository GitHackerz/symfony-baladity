<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateurs
 *
 * @ORM\Table(name="utilisateurs", uniqueConstraints={@ORM\UniqueConstraint(name="id_citoyen", columns={"idCitoyen"})})
 * @ORM\Entity
 */
class Utilisateurs
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="numtel", type="integer", nullable=false)
     */
    private $numtel;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @var string|null
     *
     * @ORM\Column(name="heuredebut", type="string", length=200, nullable=true, options={"default"="NULL"})
     */
    private $heuredebut = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="heurefin", type="string", length=200, nullable=true, options={"default"="NULL"})
     */
    private $heurefin = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $image = 'NULL';

    /**
     * @var \Citoyen
     *
     * @ORM\ManyToOne(targetEntity="Citoyen")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCitoyen", referencedColumnName="id")
     * })
     */
    private $idcitoyen;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Projet", mappedBy="user")
     */
    private $projet = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projet = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
