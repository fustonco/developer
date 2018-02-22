<?php

namespace FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PedidoAnexo
 */
class PedidoAnexo
{
    /**
     * @var string
     */
    private $ativo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FinanceiroBundle\Entity\Anexo
     */
    private $idanexo;

    /**
     * @var \FinanceiroBundle\Entity\Pedido
     */
    private $idpedido;


    /**
     * Set ativo
     *
     * @param string $ativo
     * @return PedidoAnexo
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

    /**
     * Set idanexo
     *
     * @param \FinanceiroBundle\Entity\Anexo $idanexo
     * @return PedidoAnexo
     */
    public function setIdanexo(\FinanceiroBundle\Entity\Anexo $idanexo = null)
    {
        $this->idanexo = $idanexo;

        return $this;
    }

    /**
     * Get idanexo
     *
     * @return \FinanceiroBundle\Entity\Anexo 
     */
    public function getIdanexo()
    {
        return $this->idanexo;
    }

    /**
     * Set idpedido
     *
     * @param \FinanceiroBundle\Entity\Pedido $idpedido
     * @return PedidoAnexo
     */
    public function setIdpedido(\FinanceiroBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \FinanceiroBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }
}
