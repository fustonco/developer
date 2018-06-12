<?php

namespace ChefeBundle\Entity;

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
     * @var \ChefeBundle\Entity\StatusPagamento
     */
    private $idstatus;

    /**
     * @var \ChefeBundle\Entity\Pedido
     */
    private $idpedido;

    /**
     * @var \ChefeBundle\Entity\TipoPagamento
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
     * @param \ChefeBundle\Entity\StatusPagamento $idstatus
     * @return Pagamento
     */
    public function setIdstatus(\ChefeBundle\Entity\StatusPagamento $idstatus = null)
    {
        $this->idstatus = $idstatus;

        return $this;
    }

    /**
     * Get idstatus
     *
     * @return \ChefeBundle\Entity\StatusPagamento 
     */
    public function getIdstatus()
    {
        return $this->idstatus;
    }

    /**
     * Set idpedido
     *
     * @param \ChefeBundle\Entity\Pedido $idpedido
     * @return Pagamento
     */
    public function setIdpedido(\ChefeBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \ChefeBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }

    /**
     * Set idtipo
     *
     * @param \ChefeBundle\Entity\TipoPagamento $idtipo
     * @return Pagamento
     */
    public function setIdtipo(\ChefeBundle\Entity\TipoPagamento $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \ChefeBundle\Entity\TipoPagamento 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }
    /**
     * @var \ChefeBundle\Entity\Cartao
     */
    private $cartao;

    /**
     * @var \ChefeBundle\Entity\Conta
     */
    private $conta;


    /**
     * Set cartao
     *
     * @param \ChefeBundle\Entity\Cartao $cartao
     * @return Pagamento
     */
    public function setCartao(\ChefeBundle\Entity\Cartao $cartao = null)
    {
        $this->cartao = $cartao;

        return $this;
    }

    /**
     * Get cartao
     *
     * @return \ChefeBundle\Entity\Cartao 
     */
    public function getCartao()
    {
        return $this->cartao;
    }

    /**
     * Set conta
     *
     * @param \ChefeBundle\Entity\Conta $conta
     * @return Pagamento
     */
    public function setConta(\ChefeBundle\Entity\Conta $conta = null)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * Get conta
     *
     * @return \ChefeBundle\Entity\Conta 
     */
    public function getConta()
    {
        return $this->conta;
    }
}
