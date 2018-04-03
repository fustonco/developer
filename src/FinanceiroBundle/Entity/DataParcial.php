<?php

namespace FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataParcial
 */
class DataParcial
{
    /**
     * @var string
     */
    private $valor;

    /**
     * @var \DateTime
     */
    private $dataPagamento;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FinanceiroBundle\Entity\Parcelas
     */
    private $idparcela;


    /**
     * Set valor
     *
     * @param string $valor
     * @return DataParcial
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
    }

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
     * Set idparcela
     *
     * @param \FinanceiroBundle\Entity\Parcelas $idparcela
     * @return DataParcial
     */
    public function setIdparcela(\FinanceiroBundle\Entity\Parcelas $idparcela = null)
    {
        $this->idparcela = $idparcela;

        return $this;
    }

    /**
     * Get idparcela
     *
     * @return \FinanceiroBundle\Entity\Parcelas 
     */
    public function getIdparcela()
    {
        return $this->idparcela;
    }
}
