<?php

namespace FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FuncionarioChefe
 */
class FuncionarioChefe
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FinanceiroBundle\Entity\Funcionario
     */
    private $idfuncionario;


    /**
     * Set status
     *
     * @param string $status
     * @return FuncionarioChefe
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
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
     * Set idfuncionario
     *
     * @param \FinanceiroBundle\Entity\Funcionario $idfuncionario
     * @return FuncionarioChefe
     */
    public function setIdfuncionario(\FinanceiroBundle\Entity\Funcionario $idfuncionario = null)
    {
        $this->idfuncionario = $idfuncionario;

        return $this;
    }

    /**
     * Get idfuncionario
     *
     * @return \FinanceiroBundle\Entity\Funcionario 
     */
    public function getIdfuncionario()
    {
        return $this->idfuncionario;
    }
}
