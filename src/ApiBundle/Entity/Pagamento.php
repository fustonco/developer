<?php

namespace ApiBundle\Entity;

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
     * @var \ApiBundle\Entity\StatusPagamento
     */
    private $idstatus;

    /**
     * @var \ApiBundle\Entity\Pedido
     */
    private $idpedido;

    /**
     * @var \ApiBundle\Entity\TipoPagamento
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
     * @param \ApiBundle\Entity\StatusPagamento $idstatus
     * @return Pagamento
     */
    public function setIdstatus(\ApiBundle\Entity\StatusPagamento $idstatus = null)
    {
        $this->idstatus = $idstatus;

        return $this;
    }

    /**
     * Get idstatus
     *
     * @return \ApiBundle\Entity\StatusPagamento 
     */
    public function getIdstatus()
    {
        return $this->idstatus;
    }

    /**
     * Set idpedido
     *
     * @param \ApiBundle\Entity\Pedido $idpedido
     * @return Pagamento
     */
    public function setIdpedido(\ApiBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \ApiBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }

    /**
     * Set idtipo
     *
     * @param \ApiBundle\Entity\TipoPagamento $idtipo
     * @return Pagamento
     */
    public function setIdtipo(\ApiBundle\Entity\TipoPagamento $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \ApiBundle\Entity\TipoPagamento 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }
    /**
     * @var \ApiBundle\Entity\Cartao
     */
    private $cartao;

    /**
     * @var \ApiBundle\Entity\Conta
     */
    private $conta;


    /**
     * Set cartao
     *
     * @param \ApiBundle\Entity\Cartao $cartao
     * @return Pagamento
     */
    public function setCartao(\ApiBundle\Entity\Cartao $cartao = null)
    {
        $this->cartao = $cartao;

        return $this;
    }

    /**
     * Get cartao
     *
     * @return \ApiBundle\Entity\Cartao 
     */
    public function getCartao()
    {
        return $this->cartao;
    }

    /**
     * Set conta
     *
     * @param \ApiBundle\Entity\Conta $conta
     * @return Pagamento
     */
    public function setConta(\ApiBundle\Entity\Conta $conta = null)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * Get conta
     *
     * @return \ApiBundle\Entity\Conta 
     */
    public function getConta()
    {
        return $this->conta;
    }
}
