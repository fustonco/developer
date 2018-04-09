<?php

namespace LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataParcial
 */
class DataParcial
{
    /**
     * @var \DateTime
     */
    private $dataPagamento;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \LoginBundle\Entity\Parcelas
     */
    private $valor;


    /**
     * Set dataPagamento
     *
     * @param \DateTime $dataPagamento
     * @return DataParcial
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->dataPagamento = $dataPagamento;

        return $this;
    }

    /**
     * Get dataPagamento
     *
     * @return \DateTime 
     */
    public function getDataPagamento()
    {
        return $this->dataPagamento;
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

    /**
     * Set valor
     *
     * @param \LoginBundle\Entity\Parcelas $valor
     * @return DataParcial
     */
    public function setValor(\LoginBundle\Entity\Parcelas $valor = null)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return \LoginBundle\Entity\Parcelas 
     */
    public function getValor()
    {
        return $this->valor;
    }
    /**
     * @var \LoginBundle\Entity\Parcelas
     */
    private $idparcela;


    /**
     * Set idparcela
     *
     * @param \LoginBundle\Entity\Parcelas $idparcela
     * @return DataParcial
     */
    public function setIdparcela(\LoginBundle\Entity\Parcelas $idparcela = null)
    {
        $this->idparcela = $idparcela;

        return $this;
    }

    /**
     * Get idparcela
     *
     * @return \LoginBundle\Entity\Parcelas 
     */
    public function getIdparcela()
    {
        return $this->idparcela;
    }
}
