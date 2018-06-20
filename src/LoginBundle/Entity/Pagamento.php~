<?php

namespace LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pagamento
 */
class Pagamento
{
    /**
     * @var string
     */
    private $valor;

    /**
     * @var integer
     */
    private $parcelado;

    /**
     * @var string
     */
    private $pagamentocol;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \LoginBundle\Entity\TipoPagamento
     */
    private $idtipo;

    /**
     * @var \LoginBundle\Entity\Pedido
     */
    private $idpedido;


    /**
     * Set valor
     *
     * @param string $valor
     * @return Pagamento
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set parcelado
     *
     * @param integer $parcelado
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
     * @return integer 
     */
    public function getParcelado()
    {
        return $this->parcelado;
    }

    /**
     * Set pagamentocol
     *
     * @param string $pagamentocol
     * @return Pagamento
     */
    public function setPagamentocol($pagamentocol)
    {
        $this->pagamentocol = $pagamentocol;

        return $this;
    }

    /**
     * Get pagamentocol
     *
     * @return string 
     */
    public function getPagamentocol()
    {
        return $this->pagamentocol;
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
     * Set idtipo
     *
     * @param \LoginBundle\Entity\TipoPagamento $idtipo
     * @return Pagamento
     */
    public function setIdtipo(\LoginBundle\Entity\TipoPagamento $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \LoginBundle\Entity\TipoPagamento 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }

    /**
     * Set idpedido
     *
     * @param \LoginBundle\Entity\Pedido $idpedido
     * @return Pagamento
     */
    public function setIdpedido(\LoginBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \LoginBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }
    /**
     * @var string
     */
    private $valorIntegral;

    /**
     * @var \LoginBundle\Entity\StatusPagamento
     */
    private $idstatus;


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
     * Set idstatus
     *
     * @param \LoginBundle\Entity\StatusPagamento $idstatus
     * @return Pagamento
     */
    public function setIdstatus(\LoginBundle\Entity\StatusPagamento $idstatus = null)
    {
        $this->idstatus = $idstatus;

        return $this;
    }

    /**
     * Get idstatus
     *
     * @return \LoginBundle\Entity\StatusPagamento 
     */
    public function getIdstatus()
    {
        return $this->idstatus;
    }
    /**
     * @var \LoginBundle\Entity\Cartao
     */
    private $cartao;

    /**
     * @var \LoginBundle\Entity\Conta
     */
    private $conta;


    /**
     * Set cartao
     *
     * @param \LoginBundle\Entity\Cartao $cartao
     * @return Pagamento
     */
    public function setCartao(\LoginBundle\Entity\Cartao $cartao = null)
    {
        $this->cartao = $cartao;

        return $this;
    }

    /**
     * Get cartao
     *
     * @return \LoginBundle\Entity\Cartao 
     */
    public function getCartao()
    {
        return $this->cartao;
    }

    /**
     * Set conta
     *
     * @param \LoginBundle\Entity\Conta $conta
     * @return Pagamento
     */
    public function setConta(\LoginBundle\Entity\Conta $conta = null)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * Get conta
     *
     * @return \LoginBundle\Entity\Conta 
     */
    public function getConta()
    {
        return $this->conta;
    }
}
