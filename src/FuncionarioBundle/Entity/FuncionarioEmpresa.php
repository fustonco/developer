<?php

namespace FuncionarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FuncionarioEmpresa
 */
class FuncionarioEmpresa
{
    /**
     * @var string
     */
    private $ativo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FuncionarioBundle\Entity\Funcionario
     */
    private $idfuncionario;

    /**
     * @var \FuncionarioBundle\Entity\Empresa
     */
    private $idempresa;


    /**
     * Set ativo
     *
     * @param string $ativo
     * @return FuncionarioEmpresa
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return string 
     */
    public function getAtivo()
    {
        return $this->ativo;
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
     * @param \FuncionarioBundle\Entity\Funcionario $idfuncionario
     * @return FuncionarioEmpresa
     */
    public function setIdfuncionario(\FuncionarioBundle\Entity\Funcionario $idfuncionario = null)
    {
        $this->idfuncionario = $idfuncionario;

        return $this;
    }

    /**
     * Get idfuncionario
     *
     * @return \FuncionarioBundle\Entity\Funcionario 
     */
    public function getIdfuncionario()
    {
        return $this->idfuncionario;
    }

    /**
     * Set idempresa
     *
     * @param \FuncionarioBundle\Entity\Empresa $idempresa
     * @return FuncionarioEmpresa
     */
    public function setIdempresa(\FuncionarioBundle\Entity\Empresa $idempresa = null)
    {
        $this->idempresa = $idempresa;

        return $this;
    }

    /**
     * Get idempresa
     *
     * @return \FuncionarioBundle\Entity\Empresa 
     */
    public function getIdempresa()
    {
        return $this->idempresa;
    }
}
