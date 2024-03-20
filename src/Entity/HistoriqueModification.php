<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoriqueModification
 *
 * @ORM\Table(name="historique_modification")
 * @ORM\Entity
 */
class HistoriqueModification
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="id_association", type="integer", nullable=false)
     */
    private $idAssociation;


}
