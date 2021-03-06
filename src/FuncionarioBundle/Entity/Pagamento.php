<?php

namespace FuncionarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pagamento
 */
class Pagamento
{
    /**
     * @var string
     */
    private $valorIntegral;

    /**
     * @var string
     */
    private $parcelado;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FuncionarioBundle\Entity\StatusPagamento
     */
    private $idstatus;

    /**
     * @var \FuncionarioBundle\Entity\Pedido
     */
    private $idpedido;

    /**
     * @var \FuncionarioBundle\Entity\TipoPagamento
     */
    private $idtipo;


    /**
     * Set valorIntegral
     *
     * @param string $valorIntegral
     * @return Pagamento
     */
    public function setValorIntegral($valorIntegral)
    {
        $this->valorIntegral = $valorIntegral;

        return $this;
    }

    /**
     * Get valorIntegral
     *
     * @return string 
     */
    public function getValorIntegral()
    {
        return $this->valorIntegral;
    }

    /**
     * Set parcelado
     *
     * @param string $parcelado
     * @return Pagamento
     */
    public function setParcelado($parcelado)
    {
        $this->parcelado = $parcelado;

        return $this;
    }

    /**
     * Get parcelado
     *
     * @return string 
     */
    public function getParcelado()
    {
        return $this->parcelado;
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
     * Set idstatus
     *
     * @param \FuncionarioBundle\Entity\StatusPagamento $idstatus
     * @return Pagamento
     */
    public function setIdstatus(\FuncionarioBundle\Entity\StatusPagamento $idstatus = null)
    {
        $this->idstatus = $idstatus;

        return $this;
    }

    /**
     * Get idstatus
     *
     * @return \FuncionarioBundle\Entity\StatusPagamento 
     */
    public function getIdstatus()
    {
        return $this->idstatus;
    }

    /**
     * Set idpedido
     *
     * @param \FuncionarioBundle\Entity\Pedido $idpedido
     * @return Pagamento
     */
    public function setIdpedido(\FuncionarioBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \FuncionarioBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }

    /**
     * Set idtipo
     *
     * @param \FuncionarioBundle\Entity\TipoPagamento $idtipo
     * @return Pagamento
     */
    public function setIdtipo(\FuncionarioBundle\Entity\TipoPagamento $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \FuncionarioBundle\Entity\TipoPagamento 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }
    /**
     * @var \FuncionarioBundle\Entity\Cartao
     */
    private $cartao;

    /**
     * @var \FuncionarioBundle\Entity\Conta
     */
    private $conta;


    /**
     * Set cartao
     *
     * @param \FuncionarioBundle\Entity\Cartao $cartao
     * @return Pagamento
     */
    public function setCartao(\FuncionarioBundle\Entity\Cartao $cartao = null)
    {
        $this->cartao = $cartao;

        return $this;
    }

    /**
     * Get cartao
     *
     * @return \FuncionarioBundle\Entity\Cartao 
     */
    public function getCartao()
    {
        return $this->cartao;
    }

    /**
     * Set conta
     *
     * @param \FuncionarioBundle\Entity\Conta $conta
     * @return Pagamento
     */
    public function setConta(\FuncionarioBundle\Entity\Conta $conta = null)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * Get conta
     *
     * @return \FuncionarioBundle\Entity\Conta 
     */
    public function getConta()
    {
        return $this->conta;
    }
}
