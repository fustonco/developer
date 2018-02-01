<?php

namespace ChefeBundle\Entity;

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
     * @var \ChefeBundle\Entity\Pedido
     */
    private $idpedido;

    /**
     * @var \ChefeBundle\Entity\Anexo
     */
    private $idanexo;


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
     * Set idpedido
     *
     * @param \ChefeBundle\Entity\Pedido $idpedido
     * @return PedidoAnexo
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
     * Set idanexo
     *
     * @param \ChefeBundle\Entity\Anexo $idanexo
     * @return PedidoAnexo
     */
    public function setIdanexo(\ChefeBundle\Entity\Anexo $idanexo = null)
    {
        $this->idanexo = $idanexo;

        return $this;
    }

    /**
     * Get idanexo
     *
     * @return \ChefeBundle\Entity\Anexo 
     */
    public function getIdanexo()
    {
        return $this->idanexo;
    }
}
