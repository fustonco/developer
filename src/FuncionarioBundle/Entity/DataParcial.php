<?php

namespace FuncionarioBundle\Entity;

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
     * @var \FuncionarioBundle\Entity\Parcelas
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
     * @param \FuncionarioBundle\Entity\Parcelas $valor
     * @return DataParcial
     */
    public function setValor(\FuncionarioBundle\Entity\Parcelas $valor = null)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return \FuncionarioBundle\Entity\Parcelas 
     */
    public function getValor()
    {
        return $this->valor;
    }
}
