<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeDocument
 *
 * @ORM\Table(name="demande_document", indexes={@ORM\Index(name="fk_id_client", columns={"id_client"}), @ORM\Index(name="fk_id_document", columns={"id_document"})})
 * @ORM\Entity
 */
class DemandeDocument
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ddoc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDdoc;

    /**
     * @var string
     *
     * @ORM\Column(name="type_ddoc", type="string", length=255, nullable=false)
     */
    private $typeDdoc;

    /**
     * @var string
     *
     * @ORM\Column(name="description_ddoc", type="string", length=255, nullable=false)
     */
    private $descriptionDdoc;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_ddoc", type="string", length=255, nullable=false)
     */
    private $statutDdoc;

    /**
     * @var string
     *
     * @ORM\Column(name="date_ddoc", type="string", length=255, nullable=false)
     */
    private $dateDdoc;

    /**
     * @var string
     *
     * @ORM\Column(name="date_traitement_ddoc", type="string", length=255, nullable=false, options={"default"="'N/A'"})
     */
    private $dateTraitementDdoc = '\'N/A\'';

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     * })
     */
    private $idClient;

    /**
     * @var \Documents
     *
     * @ORM\ManyToOne(targetEntity="Documents")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_document", referencedColumnName="id_doc")
     * })
     */
    private $idDocument;


}
