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
     * @var string
     */
    private $statusPag;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FuncionarioBundle\Entity\TipoPagamento
     */
    private $idtipo;

    /**
     * @var \FuncionarioBundle\Entity\Pedido
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
}
