<?php

namespace FuncionarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoPessoa
 */
class TipoPessoa
{
    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $ativo;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set nome
     *
     * @param string $nome
     * @return TipoPessoa
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set ativo
     *
     * @param string $ativo
     * @return TipoPessoa
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
}
