<?php

namespace LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FuncionarioPermissao
 */
class FuncionarioPermissao
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
     * @var \LoginBundle\Entity\Permissao
     */
    private $idpermissao;

    /**
     * @var \LoginBundle\Entity\Funcionario
     */
    private $idfuncionario;


    /**
     * Set ativo
     *
     * @param string $ativo
     * @return FuncionarioPermissao
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
     * Set idpermissao
     *
     * @param \LoginBundle\Entity\Permissao $idpermissao
     * @return FuncionarioPermissao
     */
    public function setIdpermissao(\LoginBundle\Entity\Permissao $idpermissao = null)
    {
        $this->idpermissao = $idpermissao;

        return $this;
    }

    /**
     * Get idpermissao
     *
     * @return \LoginBundle\Entity\Permissao 
     */
    public function getIdpermissao()
    {
        return $this->idpermissao;
    }

    /**
     * Set idfuncionario
     *
     * @param \LoginBundle\Entity\Funcionario $idfuncionario
     * @return FuncionarioPermissao
     */
    public function setIdfuncionario(\LoginBundle\Entity\Funcionario $idfuncionario = null)
    {
        $this->idfuncionario = $idfuncionario;

        return $this;
    }

    /**
     * Get idfuncionario
     *
     * @return \LoginBundle\Entity\Funcionario 
     */
    public function getIdfuncionario()
    {
        return $this->idfuncionario;
    }
}
