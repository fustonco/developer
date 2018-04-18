<?php

namespace ChefeBundle\Entity;

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
     * @var \ChefeBundle\Entity\Parcelas
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
     * @param \ChefeBundle\Entity\Parcelas $valor
     * @return DataParcial
     */
    public function setValor(\ChefeBundle\Entity\Parcelas $valor = null)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return \ChefeBundle\Entity\Parcelas 
     */
    public function getValor()
    {
        return $this->valor;
    }
    /**
     * @var \ChefeBundle\Entity\Parcelas
     */
    private $idparcela;


    /**
     * Set idparcela
     *
     * @param \ChefeBundle\Entity\Parcelas $idparcela
     * @return DataParcial
     */
    public function setIdparcela(\ChefeBundle\Entity\Parcelas $idparcela = null)
    {
        $this->idparcela = $idparcela;

        return $this;
    }

    /**
     * Get idparcela
     *
     * @return \ChefeBundle\Entity\Parcelas 
     */
    public function getIdparcela()
    {
        return $this->idparcela;
    }
}
