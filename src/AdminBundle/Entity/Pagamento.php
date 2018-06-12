<?php

namespace AdminBundle\Entity;

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
     * @var string
     */
    private $statusPag;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AdminBundle\Entity\TipoPagamento
     */
    private $idtipo;

    /**
     * @var \AdminBundle\Entity\Pedido
     */
    private $idpedido;


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
     * Set statusPag
     *
     * @param string $statusPag
     * @return Pagamento
     */
    public function setStatusPag($statusPag)
    {
        $this->statusPag = $statusPag;

        return $this;
    }

    /**
     * Get statusPag
     *
     * @return string 
     */
    public function getStatusPag()
    {
        return $this->statusPag;
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
     * @param \AdminBundle\Entity\TipoPagamento $idtipo
     * @return Pagamento
     */
    public function setIdtipo(\AdminBundle\Entity\TipoPagamento $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \AdminBundle\Entity\TipoPagamento 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }

    /**
     * Set idpedido
     *
     * @param \AdminBundle\Entity\Pedido $idpedido
     * @return Pagamento
     */
    public function setIdpedido(\AdminBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \AdminBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }
    /**
     * @var \AdminBundle\Entity\StatusPagamento
     */
    private $idstatus;


    /**
     * Set idstatus
     *
     * @param \AdminBundle\Entity\StatusPagamento $idstatus
     * @return Pagamento
     */
    public function setIdstatus(\AdminBundle\Entity\StatusPagamento $idstatus = null)
    {
        $this->idstatus = $idstatus;

        return $this;
    }

    /**
     * Get idstatus
     *
     * @return \AdminBundle\Entity\StatusPagamento 
     */
    public function getIdstatus()
    {
        return $this->idstatus;
    }
    /**
     * @var \AdminBundle\Entity\Cartao
     */
    private $cartao;

    /**
     * @var \AdminBundle\Entity\Conta
     */
    private $conta;


    /**
     * Set cartao
     *
     * @param \AdminBundle\Entity\Cartao $cartao
     * @return Pagamento
     */
    public function setCartao(\AdminBundle\Entity\Cartao $cartao = null)
    {
        $this->cartao = $cartao;

        return $this;
    }

    /**
     * Get cartao
     *
     * @return \AdminBundle\Entity\Cartao 
     */
    public function getCartao()
    {
        return $this->cartao;
    }

    /**
     * Set conta
     *
     * @param \AdminBundle\Entity\Conta $conta
     * @return Pagamento
     */
    public function setConta(\AdminBundle\Entity\Conta $conta = null)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * Get conta
     *
     * @return \AdminBundle\Entity\Conta 
     */
    public function getConta()
    {
        return $this->conta;
    }
}
