<?php

namespace FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conta
 */
class Conta
{
    /**
     * @var string
     */
    private $banco;

    /**
     * @var string
     */
    private $agencia;

    /**
     * @var string
     */
    private $conta;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $cnpj;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FinanceiroBundle\Entity\ContaTipo
     */
    private $tipo;


    /**
     * Set banco
     *
     * @param string $banco
     * @return Conta
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return string 
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set agencia
     *
     * @param string $agencia
     * @return Conta
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * Get agencia
     *
     * @return string 
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set conta
     *
     * @param string $conta
     * @return Conta
     */
    public function setConta($conta)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * Get conta
     *
     * @return string 
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Set cpf
     *
     * @param string $cpf
     * @return Conta
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set cnpj
     *
     * @param string $cnpj
     * @return Conta
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get cnpj
     *
     * @return string 
     */
    public function getCnpj()
    {
        return $this->cnpj;
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
     * Set tipo
     *
     * @param \FinanceiroBundle\Entity\ContaTipo $tipo
     * @return Conta
     */
    public function setTipo(\FinanceiroBundle\Entity\ContaTipo $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \FinanceiroBundle\Entity\ContaTipo 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
