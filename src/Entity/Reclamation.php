<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation")
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idReclamation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="typeReclamation", type="string", length=50, nullable=false)
     */
    private $typereclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionReclamation", type="string", length=50, nullable=false)
     */
    private $descriptionreclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="statutReclamation", type="string", length=50, nullable=false)
     */
    private $statutreclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="dateReclamation", type="string", length=20, nullable=false)
     */
    private $datereclamation;


}
