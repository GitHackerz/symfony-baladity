<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documents
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity
 */
class Documents
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_doc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="type_doc", type="string", length=255, nullable=false)
     */
    private $typeDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_doc", type="string", length=255, nullable=false)
     */
    private $statutDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="date_emission_doc", type="string", length=255, nullable=false)
     */
    private $dateEmissionDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="date_expiration_doc", type="string", length=255, nullable=false)
     */
    private $dateExpirationDoc;

    /**
     * @var bool
     *
     * @ORM\Column(name="estarchive", type="boolean", nullable=false)
     */
    private $estarchive;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_req", type="integer", nullable=false)
     */
    private $nbReq = '0';


}
