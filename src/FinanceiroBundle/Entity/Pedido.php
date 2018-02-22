<?php

namespace FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pedido
 */
class Pedido
{
    /**
     * @var string
     */
    private $codigo;

    /**
     * @var \DateTime
     */
    private $dataPedido;

    /**
     * @var string
     */
    private $valor;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var string
     */
    private $ativo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \FinanceiroBundle\Entity\Fornecedor
     */
    private $idfornecedor;

    /**
     * @var \FinanceiroBundle\Entity\StatusPedido
     */
    private $status;

    /**
     * @var \FinanceiroBundle\Entity\TipoPedido
     */
    private $idtipo;


    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Pedido
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set dataPedido
     *
     * @param \DateTime $dataPedido
     * @return Pedido
     */
    public function setDataPedido($dataPedido)
    {
        $this->dataPedido = $dataPedido;

        return $this;
    }

    /**
     * Get dataPedido
     *
     * @return \DateTime 
     */
    public function getDataPedido()
    {
        return $this->dataPedido;
    }

    /**
     * Set valor
     *
     * @param string $valor
     * @return Pedido
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
     * Set descricao
     *
     * @param string $descricao
     * @return Pedido
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string 
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set ativo
     *
     * @param string $ativo
     * @return Pedido
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
     * Set idfornecedor
     *
     * @param \FinanceiroBundle\Entity\Fornecedor $idfornecedor
     * @return Pedido
     */
    public function setIdfornecedor(\FinanceiroBundle\Entity\Fornecedor $idfornecedor = null)
    {
        $this->idfornecedor = $idfornecedor;

        return $this;
    }

    /**
     * Get idfornecedor
     *
     * @return \FinanceiroBundle\Entity\Fornecedor 
     */
    public function getIdfornecedor()
    {
        return $this->idfornecedor;
    }

    /**
     * Set status
     *
     * @param \FinanceiroBundle\Entity\StatusPedido $status
     * @return Pedido
     */
    public function setStatus(\FinanceiroBundle\Entity\StatusPedido $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \FinanceiroBundle\Entity\StatusPedido 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set idtipo
     *
     * @param \FinanceiroBundle\Entity\TipoPedido $idtipo
     * @return Pedido
     */
    public function setIdtipo(\FinanceiroBundle\Entity\TipoPedido $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \FinanceiroBundle\Entity\TipoPedido 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }
}
