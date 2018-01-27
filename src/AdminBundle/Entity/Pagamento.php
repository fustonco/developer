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
     * @var \AdminBundle\Entity\TipoPagamento
     */
    private $idtipo;

    /**
     * @var \AdminBundle\Entity\Pedido
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
}
