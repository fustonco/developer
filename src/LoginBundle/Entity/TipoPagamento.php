<?php

namespace LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoPagamento
 */
class TipoPagamento
{
    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $descriao;

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
     * @return TipoPagamento
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
     * Set descriao
     *
     * @param string $descriao
     * @return TipoPagamento
     */
    public function setDescriao($descriao)
    {
        $this->descriao = $descriao;

        return $this;
    }

    /**
     * Get descriao
     *
     * @return string 
     */
    public function getDescriao()
    {
        return $this->descriao;
    }

    /**
     * Set ativo
     *
     * @param string $ativo
     * @return TipoPagamento
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
