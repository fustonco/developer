<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documento
 */
class Documento
{
    /**
     * @var string
     */
    private $numero;

    /**
     * @var string
     */
    private $tipoDocumento;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set numero
     *
     * @param string $numero
     * @return Documento
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     * @return Documento
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
