<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponserec
 *
 * @ORM\Table(name="reponserec", indexes={@ORM\Index(name="cle", columns={"idRec"})})
 * @ORM\Entity
 */
class Reponserec
{
    /**
     * @var int
     *
     * @ORM\Column(name="idRep", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrep;

    /**
     * @var \Reclamation
     *
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idRec", referencedColumnName="idReclamation")
     * })
     */
    private $idrec;


}
